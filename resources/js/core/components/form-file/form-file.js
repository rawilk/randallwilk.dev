import idMixin from '../../mixins/component/id';
import formMixin from '../../mixins/component/form';
import formStateMixin from '../../mixins/component/formState';
import formCustomMixin from '../../mixins/component/formCustom';
import { arrayFrom } from '../../utils/array';

export default {
    mixins: [idMixin, formMixin, formStateMixin, formCustomMixin],

    props: {
        accept: {
            type: String,
            default: ''
        },
        // Instruct input to capture from camera
        capture: {
            type: Boolean,
            default: false
        },
        placeholder: {
            type: String,
            default: undefined
        },
        multiple: {
            type: Boolean,
            default: false
        },
        directory: {
            type: Boolean,
            default: false
        },
        noTraverse: {
            type: Boolean,
            default: false
        },
        noDrop: {
            type: Boolean,
            default: false
        }
    },

    computed: {
        /**
         * Determine the label.
         *
         * @returns {*}
         */
        selectLabel () {
            // No file chosen
            if (! this.selectedFile || this.selectedFile.length === 0) {
                return this.placeholder;
            }

            // Multiple files
            if (this.multiple) {
                if (this.selectedFile.length === 1) {
                    return this.selectedFile[0].name;
                }

                return this.selectedFile.map(file => file.name).join(', ');
            }

            // Single file
            return this.selectedFile.name;
        }
    },

    data () {
        return {
            selectedFile: null,
            dragging: false,
            hasFocus: false
        };
    },

    render (h) {
        // Form Input
        const input = h('input', {
            ref: 'input',
            class: [
                {
                    'form-control-file': this.plain,
                    'custom-file-input': this.custom,
                    focus: this.custom && this.hasFocus
                },
                this.stateClass
            ],
            attrs: {
                type: 'file',
                id: this.safeId(),
                name: this.name,
                disabled: this.disabled,
                required: this.required,
                capture: this.capture || null,
                accept: this.accept || null,
                multiple: this.multiple,
                webkitdirectory: this.directory,
                'aria-required': this.required ? 'true' : null,
                'aria-describedby': this.plain ? null : this.safeId('_BV_file_control_')
            },
            on: {
                change: this.onFileChange,
                focusin: this.focusHandler,
                focusout: this.focusHandler
            }
        });

        if (this.plain) {
            return input;
        }

        // Overlay Labels
        const label = h(
            'label',
            {
                class: ['custom-file-label', this.dragging ? 'dragging' : null],
                attrs: {
                    id: this.safeId('_BV_file_control_')
                }
            },
            this.selectLabel
        );

        // Return rendered custom file input
        return h(
            'div',
            {
                class: ['custom-file', 'b-form-file', this.stateClass],
                attrs: { id: this.safeId('_BV_file_outer_') },
                on: { dragover: this.dragover }
            },
            [input, label]
        );
    },

    methods: {
        /**
         * Handle drag leave.
         *
         * @param {Event} event
         */
        dragleave (event) {
            event.preventDefault();
            event.stopPropagation();

            this.dragging = false;
        },

        /**
         * Handle drag over.
         *
         * @param {Event} event
         */
        dragover (event) {
            event.preventDefault();
            event.stopPropagation();

            if (this.noDrop || ! this.custom) {
                return;
            }

            this.dragging = true;
            event.dataTransfer.dropEffect = 'copy';
        },

        /**
         * Handle drop.
         *
         * @param {Event} event
         */
        drop (event) {
            event.preventDefault();
            event.stopPropagation();

            if (this.noDrop) {
                return;
            }

            this.dragging = false;
            if (event.dataTransfer.files && event.dataTransfer.files.length > 0) {
                this.onFileChange(event);
            }
        },

        /**
         * Handle on file change.
         *
         * @param {Event} event
         */
        onFileChange (event) {
            // Always emit original event
            this.$emit('change', event);

            // Check if special `items` prop is available on event (drop mode)
            // Can be disabled by setting no-traverse
            const items = event.dataTransfer && event.dataTransfer.items;

            if (items && !this.noTraverse) {
                const queue = [];

                for (let i = 0; i < items.length; i++) {
                    const item = items[i].webkitGetAsEntry();

                    if (item) {
                        queue.push(this.traverseFileTree(item));
                    }
                }

                Promise.all(queue).then(filesArr => {
                    this.setFiles(arrayFrom(filesArr));
                });

                return;
            }

            // Normal handling
            this.setFiles(event.target.files || event.dataTransfer.files);
        },

        /**
         * Handle focus event.
         *
         * @param {Event} event
         */
        focusHandler (event) {
            this.hasFocus = ! (this.plain || event.type === 'focusout');
        },

        /**
         * Reset the input control.
         */
        reset () {
            try {
                // Wrapped in try in case IE < 11 craps out
                this.$refs.input.value = '';
            } catch (e) {}

            // IE < 11 doesn't support setting input.value to '' or null
            // So we use this little extra hack to reset the value, just in case
            // This also appears to work on modern browsers as well.
            this.$refs.input.type = '';
            this.$refs.input.type = 'file';
            this.selectedFile = this.multiple ? [] : null;
        },

        /**
         * Set the files.
         *
         * @param {array} files
         */
        setFiles (files) {
            if (! files) {
                this.selectedFile = null;

                return;
            }

            if (! this.multiple) {
                this.selectedFile = files[0];

                return;
            }

            // Convert files to array
            const filesArray = [];
            for (let i = 0; i < files.length; i++) {
                if (files[i].type.match(this.accept)) {
                    filesArray.push(files[i]);
                }
            }

            this.selectedFile = filesArray;
        },

        /**
         * Traverse the file tree.
         *
         * @param item
         * @param path
         * @returns {Promise<any>}
         */
        traverseFileTree (item, path) {
            // Based on http://stackoverflow.com/questions/3590058
            return new Promise(resolve => {
                path = path || '';
                if (item.isFile) {
                    // Get file
                    item.file(file => {
                        file.$path = path; // Inject $path to file obj
                        resolve(file);
                    });
                } else if (item.isDirectory) {
                    // Get folder contents
                    item.createReader().readEntries(entries => {
                        const queue = [];

                        for (let i = 0; i < entries.length; i++) {
                            queue.push(
                                this.traverseFileTree(entries[i], path + item.name + '/')
                            );
                        }

                        Promise.all(queue).then(filesArr => {
                            resolve(arrayFrom(filesArr));
                        });
                    });
                }
            });
        }
    },

    watch: {
        selectedFile (newVal, oldVal) {
            if (newVal === oldVal) {
                return;
            }

            if (! newVal && this.multiple) {
                this.$emit('input', []);
            } else {
                this.$emit('input', newVal);
            }
        }
    }
};
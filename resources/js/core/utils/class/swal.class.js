import swal from 'sweetalert2';
import { trans } from '../../filters/string/trans';

const defaults = {
    position: 'center',
    grow: 'row',
    reverseButtons: true,
    showLoaderOnConfirm: true,
    allowOutsideClick: () => ! swal.isLoading()
};

export class SweetAlert {
    /**
     * Initialize the class.
     *
     * @param {object} options
     */
    constructor (options = {}) {
        this.setOptions(options);
    }

    /**
     * Set the global options.
     *
     * @param {object} options
     * @returns {SweetAlert}
     */
    setOptions (options = {}) {
        this.options = Object.assign({}, defaults, options);

        return this;
    }

    /**
     * Show a confirmation dialog without making an ajax request.
     *
     * @param {object} options
     * @param {null|function} postConfirm
     */
    localConfirm (options = {}, postConfirm = null) {
        // Get translations
        const title = this.getTranslatedValue('labels.forms.delete_confirm_title', 'Are you sure?');
        const confirmButtonText = this.getTranslatedValue('labels.forms.delete_confirm_button', 'Delete');
        const cancelButtonText = this.getTranslatedValue('labels.forms.delete_confirm_cancel', 'Cancel');

        const swalOptions = Object.assign({}, this.options, {
            title,
            type: 'question',
            showCancelButton: true,
            cancelButtonText,
            confirmButtonText,
            showLoaderOnConfirm: false
        }, options);

        swal(swalOptions)
            .then(({ value }) => {
                if (value && typeof postConfirm === 'function') {
                    postConfirm(value);
                }
            });
    }

    /**
     * Show a confirmation dialog. Typically used for deleting.
     *
     * @param {object} options
     * @param {function|null} postConfirm
     */
    confirm (options = {}, postConfirm = null) {
        // Get translations
        const title = this.getTranslatedValue('labels.forms.delete_confirm_title', 'Are you sure?');
        const confirmButtonText = this.getTranslatedValue('labels.forms.delete_confirm_button', 'Delete');
        const cancelButtonText = this.getTranslatedValue('labels.forms.delete_confirm_cancel', 'Cancel');

        const swalOptions = Object.assign({}, this.options, {
            title,
            type: 'question',
            showCancelButton: true,
            cancelButtonText,
            confirmButtonText
        }, options);

        swal(swalOptions)
            .then(({ value }) => {
                if (value) {
                    // Get translations
                    let text = this.getTranslatedValue('requests.general_success_message', 'Your request has been processed.');

                    if (value.status === 'success') {
                        text = value.message;

                        if (typeof postConfirm === 'function') {
                            postConfirm(value);
                        }
                    }

                    const title = this.getTranslatedValue('labels.forms.delete_confirm_success_title', 'Success!');
                    swal({
                        type: 'success',
                        title,
                        html: text
                    });
                }
            });
    }

    /**
     * Show the given validation error.
     *
     * @param {string} message
     */
    showValidationError (message) {
        swal.showValidationError(message);
    }

    /**
     * Attempt to get a translation for the given key.
     *
     * @param {string} key
     * @param {string} fallbackValue
     * @returns {string}
     */
    getTranslatedValue (key, fallbackValue = '') {
        try {
            return trans(key);
        } catch (e) {
            return fallbackValue;
        }
    }
}
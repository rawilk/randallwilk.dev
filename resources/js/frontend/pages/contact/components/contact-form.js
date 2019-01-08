import formable from '~/core/mixins/formable';
import Form from '~/core/utils/class/form.class';

export default {
    mixins: [formable],

    provide () {
        this.form = new Form(this.contact)
            .submitTo(this.route('frontend.contact.store').url());

        return {
            form: this.form
        };
    },

    data () {
        return {
            contact: {
                name: '',
                email: '',
                subject: '',
                message: '',
            }
        };
    },

    methods: {
        submitForm () {
            this.form.post()
                .then(data => {
                    if (data.results && data.results.sent) {
                        this.form.reset();
                    }
                })
                .catch(error => {});
        }
    }
};
export default {
    $_veeValidate: {
        validator: 'new'
    },

    data () {
        return {
            form: {}
        };
    },

    methods: {
        /**
         * Perform any logic before validation.
         */
        prevalidate () {
            // Override in component
        },

        /**
         * Validate and attempt to submit the form.
         */
        submit () {
            this.prevalidate();

            this.$validator.validate().then(valid => valid && this.submitForm());
        },

        /**
         * Submit the form to the server.
         */
        submitForm () {
            // Override in component
        }
    }
};
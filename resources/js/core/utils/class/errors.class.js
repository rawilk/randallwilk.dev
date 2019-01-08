export default class Errors {
    constructor () {
        this.errors = {};
    }

    /**
     * Determine if there are any errors.
     *
     * @returns {boolean}
     */
    any () {
        return Object.keys(this.errors).length > 0;
    }

    /**
     * Clear the given field's errors or the entire form's errors.
     *
     * @param {string|null} field
     */
    clear (field = null) {
        if (field) {
            this.errors = Object.keys(this.errors).filter(key => key !== field);

            return;
        }

        this.errors = {};
    }

    /**
     * Retrieve the first error for the given field.
     *
     * @param {string} field
     * @returns {null|string}
     */
    get (field) {
        return this.has(field) ? this.errors[field][0] : null;
    }

    /**
     * Determine if the given field has an error.
     *
     * @param {string} field
     * @returns {boolean}
     */
    has (field) {
        return this.errors.hasOwnProperty(field);
    }

    /**
     * Set the form's errors.
     *
     * @param {object} errors
     */
    record (errors) {
        this.errors = errors;
    }
}
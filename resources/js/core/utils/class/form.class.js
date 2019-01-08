import Errors from './errors.class';
import Ajax from './ajax.class';
import { Notify } from './notification.class';
import cloneDeep from 'lodash/cloneDeep';
import { isObject } from '../typeChecks';
import { isArray } from '../array';
import { hasFile, toFormData } from '../files';
import qs from 'qs';

/**
 * Private class methods
 */
const initModel = Symbol('initModel');
const mergeWithAppends = Symbol('mergeWithAppends');
const setOptions = Symbol('setOptions');

export default class Form {
    /**
     * Initialize the form.
     *
     * @param {object} model
     * @param {object} options
     */
    constructor (model, options = {}) {
        // Flag to let class know it is initialized
        this.initialized = false;

        // Initialize the form's model
        this[initModel](model);

        // Setup class options
        this[setOptions](options);

        // Initialize the error bag
        this.errors = new Errors();

        // Initialize the ajax helpers
        this.ajax = new Ajax();
    }

    /**
     * Change the model the form is bound to.
     *
     * @param {object} model
     * @param {object} options
     */
    newModel (model, options = {}) {
        // Initialize the form's model
        this[initModel](model, true);

        // Setup class options
        this[setOptions](options);
    }

    /**
     * Initialize the form's model.
     *
     * @param {object} model
     * @param {boolean} destroyOriginal
     */
    [initModel] (model, destroyOriginal = false) {
        if (destroyOriginal) {
            this.model = {};
        }

        this.model = model;

        this.originalData = cloneDeep(model);
    }

    /**
     * Merge the form's model data with any data that has been specified
     * to get appended to the request.
     *
     * @param {object} data
     * @param {boolean} transform
     * @returns {object|FormData|string}
     */
    [mergeWithAppends] (data, transform) {
        data = Object.assign({}, this.appends, data);

        if (transform) {
            if (hasFile(data)) {
                return toFormData(data);
            }

            if (this.stringify) {
                return qs.stringify(data);
            }
        }

        return data;
    }

    /**
     * Set defaults for class options.
     *
     * @param {object} options
     */
    [setOptions] (options = {}) {
        if (! this.initialized) {
            this.appends = {};
            this.attributes = null;
            this.busy = false;
            this.endpoint = null;
            this.destroyOnReset = false;
            this.stringify = false;
            this.notifySuccess = true;
            this.notifyError = true;
            this.successMessageKey = 'message';
            this.errorMessageKey = 'reason';
            this.notificationOptions = {};

            this.initialized = true;
        }

        this.updateOptions(options);
    }

    /**
     * Update the given options.
     *
     * @param {object} options
     */
    updateOptions (options) {
        const noUpdateOptions = ['initialized'];

        Object.keys(options).forEach(option => {
            if (option in this && ! noUpdateOptions.includes(option)) {
                this[option] = options[option];
            }
        });
    }

    /**
     * Get the data associated with the form.
     *
     * @param {boolean} transform
     * @returns {object|FormData|string}
     */
    data (transform = false) {
        let data = {};

        if (isArray(this.attributes) && this.attributes.length) {
            this.attributes.forEach(attribute => data[attribute] = this.model[attribute]);
        } else {
            for (let prop in this.model) {
                data[prop] = this.model[prop];
            }
        }

        return this[mergeWithAppends](data, transform);
    }

    /**
     * Add data to be appended to the form request.
     *
     * @param {object} data
     * @returns {Form}
     */
    appendData (data) {
        this.appends = Object.assign(this.appends, data);

        return this;
    }

    /**
     * Remove all appended data.
     *
     * @returns {Form}
     */
    clearAppendedData () {
        this.appends = {};

        return this;
    }

    /**
     * Customize the notification.
     *
     * @param {object} options
     * @returns {Form}
     */
    customizeNotification (options) {
        this.notificationOptions = options;

        return this;
    }

    /**
     * Prevent a notification from being shown when an error happens.
     *
     * @returns {Form}
     */
    hideErrorNotification () {
        this.notifyError = false;

        return this;
    }

    /**
     * Hide the success notification on a successful form submission.
     *
     * @returns {Form}
     */
    hideSuccessNotification () {
        this.notifySuccess = false;

        return this;
    }

    /**
     * Specify specific attributes to send from the model.
     *
     * @param {array|null} attributes
     * @returns {Form}
     */
    onlySend (attributes) {
        this.attributes = attributes;

        return this;
    }

    /**
     * Remove the given item from appended form data.
     *
     * @param {array|string} key
     * @returns {Form|*}
     */
    removeAppendedItem (key) {
        if (isArray(key)) {
            key.forEach(k => this.removeAppendedData(k));

            return;
        }

        if (key in this.appends) {
            delete this.appends[key];
        }

        return this;
    }

    /**
     * Reset the form's model to its original state.
     */
    reset () {
        if (! this.destroyOnReset) {
            for (let field in this.originalData) {
                this.model[field] = this.originalData[field];
            }

            return;
        }

        for (let field in this.model) {
            if (isArray(this.model[field])) {
                this.model[field] = [];
            } else if (isObject(this.model[field])) {
                this.model[field] = {};
            } else {
                this.model[field] = '';
            }

            // TODO: boolean check
        }
    }

    /**
     * Determines if the model data should be reset to its original state
     * or completely destroyed.
     *
     * @param {boolean} destroy
     * @returns {Form}
     */
    setDestroyOnReset (destroy = true) {
        this.destroyOnReset = destroy;

        return this;
    }

    /**
     * Set the endpoint of the form.
     *
     * @param {string} endpoint
     * @returns {Form}
     */
    submitTo (endpoint) {
        this.endpoint = endpoint;

        return this;
    }

    /**
     * Enable or disable JSON.stringify on data before sending to server.
     *
     * @param {boolean} stringify
     * @returns {Form}
     */
    setStringify (stringify = true) {
        this.stringify = stringify;

        return this;
    }

    /**
     * Update the given field on the model.
     *
     * @param {string} field
     * @param {*} value
     * @param {boolean} updateOriginal
     * @returns {Form}
     */
    updateField (field, value, updateOriginal = false) {
        this.model[field] = value;

        if (updateOriginal) {
            this.originalData[field] = value;
        }

        return this;
    }

    /**
     * Update the current model.
     *
     * @param {object} newModel
     * @returns {Form}
     */
    updateModel (newModel) {
        this[initModel](newModel);

        return this;
    }

    /**
     * Submit a GET request to the server.
     *
     * @param {string|null} endpoint
     * @returns {Promise}
     */
    get (endpoint = null) {
        return this.submit('get', this.normalizeEndpoint(endpoint));
    }

    /**
     * Submit a PATCH request to the server.
     *
     * @param {string|null} endpoint
     * @returns {Promise}
     */
    patch (endpoint = null) {
        return this.submit('patch', this.normalizeEndpoint(endpoint));
    }

    /**
     * Submit a POST request to the server.
     *
     * @param {string|null} endpoint
     * @returns {Promise}
     */
    post (endpoint = null) {
        return this.submit('post', this.normalizeEndpoint(endpoint));
    }

    /**
     * Submit a PUT request to the server.
     *
     * @param {string|null} endpoint
     * @returns {Promise}
     */
    put (endpoint = null) {
        return this.submit('put', this.normalizeEndpoint(endpoint));
    }

    /**
     * Determine the endpoint for the current request.
     *
     * @param {string|null} endpoint
     * @returns {string}
     */
    normalizeEndpoint (endpoint = null) {
        if (! endpoint || endpoint.toString().trim() === '') {
            endpoint = this.endpoint;
        }

        return endpoint;
    }

    /**
     * Submit the form to the given endpoint.
     * @param {string} requestType
     * @param {string} endpoint
     * @returns {Promise<any>}
     */
    submit (requestType, endpoint) {
        if (this.busy) {
            return;
        }

        this.busy = true;

        let data = this.data(true);

        // Clear any errors
        this.errors.clear();

        return new Promise((resolve, reject) => {
            this.ajax[requestType](endpoint, data)
                .then(data => {
                    try {
                        if (this.notifySuccess && this.successMessageKey in data && data[this.successMessageKey].toString().length) {
                            if (typeof data.status === 'undefined' || data.status === 'success') {
                                this.notify(data[this.successMessageKey], 'success');
                            }
                        }
                    } catch (e) {}

                    resolve(data);
                })
                .catch(error => {
                    if (error.data && error.data.errors && error.status === 422) {
                        // Validation errors have been returned from Laravel
                        this.errors.record(error.data.errors);
                    } else {
                        const message = this.getErrorMessage(error);

                        // Notify if error was returned
                        message && this.notify(message, 'error');
                    }

                    reject(error);
                })
                .then(() => this.busy = false);
        });
    }

    /**
     * Notify end-user of success or error.
     *
     * @param {string} message
     * @param {string} type
     */
    notify (message, type) {
        new Notify(this.notificationOptions)[type](message);
    }

    /**
     * Get an appropriate error message.
     *
     * @param {object} error
     * @returns {null|string}
     */
    getErrorMessage (error) {
        if (! this.notifyError) {
            return null;
        }

        return Ajax.getErrorMessage(error, this.errorMessageKey);
    }
}
import { isString } from '../typeChecks';
import { isArray } from '../array';
import cloneDeep from 'lodash/cloneDeep';

// Note - this class should not be your only means of authorizing actions.
// You should always use server side authorization techniques as well!

export default class Access {
    /**
     * Initialize the class.
     *
     * @param {null|object} user
     */
    constructor (user = null) {
        if (user === null) {
            try {
                this.user = Access.copyUser(window.config.user);
            } catch (e) {}
        } else {
            this.user = Access.copyUser(user);
        }
    }

    /**
     * Return a clone of the given user to remove its reference.
     *
     * @param {object} user
     * @returns {object}
     */
    static copyUser (user) {
        return cloneDeep(user);
    }

    /**
     * Determine if any of the user's roles have all access.
     *
     * @returns {boolean}
     */
    hasAllAccess () {
        if (! this.user || ! this.user.roles) {
            return false;
        }

        return this.user.roles.some(role => role.all);
    }

    /**
     * Determine if the user has the given permission.
     *
     * @param {number|string} permission
     * @returns {boolean}
     */
    hasPermissionTo (permission) {
        // If any of the user's roles have all permissions, the user can do anything
        if (this.hasAllAccess()) {
            return true;
        }

        return this.hasDirectPermission(permission) || this.hasPermissionViaRole(permission);
    }

    /**
     * Determine if the user has any of the given permissions.
     *
     * @param {array|*} permissions
     * @returns {boolean}
     */
    hasAnyPermission(...permissions) {
        if (isArray(permissions[0])) {
            permissions = permissions[0];
        }

        return permissions.some(permission => this.hasPermissionTo(permission));
    }

    /**
     * Determine if the user has all of the given permissions.
     *
     * @param {array|*} permissions
     * @returns {boolean}
     */
    hasAllPermissions(...permissions) {
        if (isArray(permissions[0])) {
            permissions = permissions[0];
        }

        return permissions.every(permission => this.hasPermissionTo(permission));
    }

    /**
     * Determine if the user has the given permission via one of its roles.
     *
     * @param {number|string} permission
     * @returns {boolean}
     */
    hasPermissionViaRole (permission) {
        if (! this.user || ! this.user.roles) {
            return false;
        }

        return this.user.roles.some(role => this.modelHasPermission(role, permission));
    }

    /**
     * Determine if the user has the given permission.
     *
     * @param {number|string} permission
     * @returns {boolean}
     */
    hasDirectPermission (permission) {
        return this.modelHasPermission(this.user, permission);
    }

    /**
     * Determine if the user has the given role or roles.
     *
     * @param {array|number|string} role
     * @param {boolean} strict
     * @returns {boolean}
     */
    hasRole (role, strict = true) {
        if (! this.user || ! this.user.roles) {
            return false;
        }

        if (! strict && this.hasAllAccess()) {
            return true;
        }

        if (isArray(role)) {
            return role.some(r => this.hasRole(r));
        }

        let field = 'id';

        if (isString(role)) {
            field = 'slug';
        }

        return this.user.roles.findIndex(r => r[field] === role) > -1;
    }

    /**
     * Determine if the user has any of the given roles.
     *
     * @param {array} roles
     * @param {boolean} strict
     * @returns {boolean}
     */
    hasAnyRole (roles, strict = true) {
        return this.hasRole(roles, strict);
    }

    /**
     * Determine if the user has all of the given roles.
     *
     * @param {array|*} roles
     * @param {boolean} strict
     * @returns {boolean}
     */
    hasAllRoles (roles, strict = true) {
        if (! isArray(roles)) {
            roles = [roles];
        }

        return roles.every(role => this.hasRole(role, strict));
    }

    /**
     * Determine if the given model has the given permission.
     *
     * @param {object|*} model
     * @param {number|string} permission
     * @returns {boolean}
     */
    modelHasPermission (model, permission) {
        if (! model || ! model.permissions) {
            return false;
        }

        let field = 'id';

        if (isString(field)) {
            field = 'slug';
        }

        return model.permissions.findIndex(perm => perm[field] === permission) > -1;
    }
}
module.exports = {
    safelist: [
        // Alerts...
        {
            pattern: /alert--.+/,
        },

        // Badges...
        {
            pattern: /badge--.+/,
        },

        // Buttons...
        {
            pattern: /button--.+/,
        },

        // Cards
        {
            pattern: /card-header--.+/,
        },

        // Form components...
        {
            pattern: /file-upload__input--.+/,
        },
        {
            pattern: /switch-toggle--.+/,
        },
        {
            pattern: /custom-select__button--.+/,
        },
        {
            pattern: /form-input--.+/,
        },

        // For dark mode...
        'filepond--panel-root',
        'filepond--root',
    ],
};

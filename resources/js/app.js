require('./bootstrap');
import { updateBreadcrumb } from './utils/update-breadcrumb';
import { updatePageTitle } from './utils/update-page-title';

window.updateBreadcrumb = updateBreadcrumb;
window.updatePageTitle = updatePageTitle;

document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('#docsScreen')) {
        require('./docs.js');
    }
});

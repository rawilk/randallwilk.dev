<?php

use App\View\Components;

return [
    /*
    |--------------------------------------------------------------------------
    | Components
    |--------------------------------------------------------------------------
    |
    | Below is a reference of the blade components that should be registered
    | for the application.
    |
    */
    'components' => [

        'html' => [
            'class' => Components\Layouts\Html::class,
            'view' => 'components.layouts.html',
        ],

        'app' => [
            'class' => Components\Layouts\App::class,
            'view' => 'components.layouts.app',
        ],

        'authentication-form' => [
            'class' => Components\Auth\AuthenticationForm::class,
            'view' => 'components.auth.authentication-form',
        ],

        'button' => [
            'class' => Components\Button\Button::class,
            'view' => 'components.button.button',
        ],

        'alert' => [
            'class' => Components\Alerts\Alert::class,
            'view' => 'components.alerts.alert',
        ],

        'session-alert' => [
            'class' => Components\Alerts\SessionAlert::class,
            'view' => 'components.alerts.session-alert',
        ],

        'notification' => [
            'class' => 'components.alerts.notification',
        ],

        'dropdown' => [
            'class' => Components\Navigation\Dropdown::class,
            'view' => 'components.navigation.dropdown',
        ],

        'dropdown-item' => [
            'class' => 'components.navigation.dropdown-item',
        ],

        'dropdown-divider' => [
            'class' => 'components.navigation.dropdown-divider',
        ],

        'admin-page-title' => [
            'class' => Components\Admin\Layout\PageTitle::class,
            'view' => 'components.admin.layout.page-title',
        ],

        'modal' => [
            'class' => Components\Modal\Modal::class,
            'view' => 'components.modal.modal',
        ],

        'dialog-modal' => [
            'class' => Components\Modal\DialogModal::class,
            'view' => 'components.modal.dialog-modal',
        ],

        'slide-over' => [
            'class' => Components\Modal\SlideOver::class,
            'view' => 'components.modal.slide-over',
        ],

        'slide-over-form' => [
            'class' => Components\Modal\SlideOverForm::class,
            'view' => 'components.modal.slide-over-form',
        ],

        'badge' => [
            'class' => Components\Elements\Badge::class,
            'view' => 'components.elements.badge',
        ],

        'info-list' => [
            'class' => 'components.lists.info-list',
        ],

        'info-list-item' => [
            'class' => 'components.lists.info-list-item',
        ],

        'table' => [
            'class' => Components\Table\Table::class,
            'view' => 'components.table.table',
        ],

        'tr' => [
            'class' => 'components.table.tr',
        ],

        'th' => [
            'class' => 'components.table.th',
        ],

        'td' => [
            'class' => 'components.table.td',
        ],

        'column-select' => [
            'class' => Components\Table\ColumnSelect::class,
            'view' => 'components.table.column-select',
        ],

        'two-column-card-form' => [
            'class' => 'components.elements.two-column-card-form',
        ],

        'card' => [
            'class' => Components\Elements\Card::class,
            'view' => 'components.elements.card',
        ],

        'action-menu' => [
            'class' => 'components.elements.action-menu',
        ],

        'inner-nav' => [
            'class' => Components\Navigation\InnerNav::class,
            'view' => 'components.navigation.inner-nav',
        ],

        'inner-nav-item' => [
            'class' => Components\Navigation\InnerNavItem::class,
            'view' => 'components.navigation.inner-nav-item',
        ],

        'tooltip' => [
            'class' => Components\Elements\Tooltip::class,
            'view' => 'components.elements.tooltip',
        ],

        'tooltip-help' => [
            'class' => 'components.elements.tooltip-help',
        ],

    ],
];

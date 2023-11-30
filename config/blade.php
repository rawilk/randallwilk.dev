<?php

declare(strict_types=1);

return [
    'defaults' => [
        'button' => [
            'ripple' => true,
            'ripple_focus' => true,
            'size' => 'md',
        ],

        'card' => [
            // Apply a CSS class to the card body by default.
            'body_class' => null,

            // Remove all padding from the card body by default.
            'flush' => false,

            // Make the card header sticky by default.
            'sticky_header' => true,

            // Define the space from the top that the sticky header will be anchored to.
            // Use a string with a unit (e.g. '1rem')
            'sticky_header_offset' => '4rem',

            // Define the z-index of the sticky header by default.
            'sticky_header_z_index' => '21',

            // Set the icon to use by default for the collapse button.
            // Tip: We will rotate the icon 180 degrees when the card is collapsed,
            // so it's advisable to use an icon that works well in both directions.
            'collapse_icon' => 'heroicon-m-chevron-down',

            // Apply the ripple effect to card actions by default.
            'action_ripple' => true,
        ],
    ],
];

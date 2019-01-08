<?php

Route::namespace('Frontend')
    ->as('frontend.')
    ->group(function () {
        includeRouteFiles(__DIR__ . '/frontend/');
    });

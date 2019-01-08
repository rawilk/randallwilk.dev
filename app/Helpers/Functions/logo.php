<?php

/**
 * Get the path for the application logo.
 *
 * @return string
 */
function appLogo() : string
{
    return asset(config('randallwilk.logo'));
}

/**
 * Get the favicon to use for the application.
 *
 * @return string
 */
function appFavicon() : string
{
    return asset(config('randallwilk.favicon'));
}
<?php

namespace App\Helpers\Classes;

class Filter
{
    /**
     * Return only letters from the given string.
     *
     * @param string $string
     * @return string
     */
    public static function alpha(string $string) : string
    {
        return preg_replace('#[^[:alpha:]]#', '', $string);
    }

    /**
     * Return only letters and numbers from the given string.
     *
     * @param string $string
     * @return string
     */
    public static function alphaNumeric(string $string) : string
    {
        return preg_replace('#[^[:alnum:]]#', '', $string);
    }

    /**
     * Return only digits from the given string.
     *
     * @param mixed $string
     * @return number|string
     */
    public static function digits($string)
    {
        // We need to replace `-` and `+` because they are allowed in `FILTER_SANITIZE_NUMBER_INT`
        $string = str_replace(['-', '+'], '', $string);

        return filter_var($string, FILTER_SANITIZE_NUMBER_INT);
    }

    /**
     * Remove tags from the given string.
     *
     * @param null|string $string
     * @return null|string
     */
    public static function strip(?string $string) : ?string
    {
        if ($string === null) {
            return $string;
        }

        $string = strip_tags($string);

        return trim($string);
    }
}

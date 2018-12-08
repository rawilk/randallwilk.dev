<?php

if (! function_exists('includeRouteFiles')) {
    /**
     * Require all route files in the given directory.
     * Searches sub-directories as well.
     *
     * @param string $dir
     */
    function includeRouteFiles($dir)
    {
        try {
            $rdi = new recursiveDirectoryIterator($dir);
            $it = new recursiveIteratorIterator($rdi);
            while ($it->valid()) {
                if (! $it->isDot() && $it->isFile() && $it->isReadable() && $it->current()->getExtension() === 'php') {
                    require $it->key();
                }
                $it->next();
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}

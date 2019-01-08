<?php

/**
 * Get all of the stored projects.
 *
 * @return array
 * @throws \Exception
 */
function getProjects() : array
{
    return getDataFromJsonFile('projects.json');
}

/**
 * Get all of the blog posts.
 *
 * @return array
 * @throws \Exception
 */
function getPosts() : array
{
    return getDataFromJsonFile('posts.json');
}

/**
 * Attempt to retrieve data from the given json file.
 *
 * @param string $filename
 * @return array
 * @throws \Exception
 */
function getDataFromJsonFile(string $filename) : array
{
    $path = resource_path("json/{$filename}");

    return cache()->rememberForever($path, function () use ($path) {
        if (! File::exists($path)) {
            return [];
        }

        return json_decode(
            File::get($path),
            true
        );
    });
}

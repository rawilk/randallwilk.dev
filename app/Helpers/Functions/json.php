<?php

/**
 * Get all of the stored projects.
 *
 * @return array
 */
function getProjects() : array
{
    return getDataFromJsonFile('projects.json');
}

/**
 * Get all of the blog posts.
 *
 * @return array
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
 */
function getDataFromJsonFile(string $filename) : array
{
    $path = resource_path("json/{$filename}");

    if (! File::exists($path)) {
        return [];
    }

    return json_decode(
        File::get($path),
        true
    );
}

<?php

/**
 * Get the given project's image.
 *
 * @param array $project
 * @return string
 */
function getProjectImage(array $project) : string
{
    if (! array_has($project, 'image') || $project['image'] === null) {
        return asset(config('randallwilk.project_placeholder'));
    }

    return asset($project['image']);
}

/**
 * Get the featured projects.
 *
 * @return array
 */
function getFeaturedProjects() : array
{
    $projects = getProjects();

    if (! array_has($projects, 'projects')) {
        return [];
    }

    $projects = collect($projects['projects']);

    return $projects->where('featured', true)->toArray();
}
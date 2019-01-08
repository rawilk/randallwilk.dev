<?php

/**
 * Get the given post's image.
 *
 * @param array $post
 * @return string
 */
function getPostImage(array $post) : string
{
    if (! array_has($post, 'image') || $post['image'] === null) {
        return config('randallwilk.project_placeholder');
    }

    return asset($post['image']);
}

/**
 * Get the latest posts.
 *
 * @param int $limit
 * @return array
 */
function latestPosts(int $limit = 4) : array
{
    $posts = getPosts();

    if (! array_has($posts, 'posts')) {
        return [];
    }

    $posts = collect($posts['posts']);

    return $posts->sortByDesc('id')->take($limit)->toArray();
}

/**
 * Get blog posts by the given category.
 *
 * @param string $category
 * @return array
 */
function postsByCategory(string $category) : array
{
    $posts = getPosts();

    if (! array_has($posts, 'posts')) {
        return [];
    }

    $posts = collect($posts['posts']);

    $category = strtolower(trim($category));

    return $posts->filter(function ($post) use ($category) {
        return \in_array($category, $post['categories'] ?? [], true);
    })->toArray();
}

/**
 * Determine if the given post category exists.
 *
 * @param string $category
 * @return bool
 */
function postCategoryExists(string $category) : bool
{
    $posts = getPosts();

    if (! array_has($posts, 'categories')) {
        return false;
    }

    $category = strtolower(trim($category));

    return array_key_exists($category, $posts['categories']);
}

/**
 * Get the display for the given post category.
 *
 * @param string $category
 * @return string|null
 */
function postCategoryDisplay(string $category) : ?string
{
    $posts = getPosts();

    if (! array_has($posts, 'categories')) {
        return false;
    }

    $category = strtolower(trim($category));

    return array_get($posts['categories'], $category, null);
}

/**
 * Get a link for the given blog post category.
 *
 * @param string $category
 * @return string
 */
function getPostCategoryLink(string $category) : string
{
    return route('frontend.posts.category', compact('category'));
}

/**
 * Get the link for the given post.
 *
 * @param array $post
 * @return string
 */
function getPostLink(array $post) : string
{
    return route('frontend.posts.view', ['post' => $post['slug']]);
}

/**
 * Determine if the given post is out dated.
 *
 * @param array $post
 * @return bool
 */
function isOldPost(array $post) : bool
{
    return now()->subYear(1)->gt(\Carbon\Carbon::parse($post['date']));
}

/**
 * Determine if old post notices can be shown for the given post.
 *
 * @param array $post
 * @return bool
 */
function canShowOldPostNotice(array $post) : bool
{
    return ! array_has($post, 'showOldNotice') || $post['showOldNotice'] === true;
}
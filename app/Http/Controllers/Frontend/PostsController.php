<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\BaseController;
use function getPostCategoryLink;
use Illuminate\Http\Response;
use function postCategoryDisplay;
use function postsByCategory;
use function postCategoryExists;

class PostsController extends BaseController
{
    /**
     * @var array
     */
    protected $posts;

    /**
     * @var null|array
     */
    protected $post;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $data = getPosts();

        $this->posts = collect(array_get($data, 'posts', []));
    }

    /**
     * Display the given post's page.
     *
     * @param string $post
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index($post)
    {
        // attempt to find the post
        $this->post = $this->findPost($post);

        abort_unless((bool) $this->post, Response::HTTP_NOT_FOUND);

        try {
            return view('frontend.posts.' . $this->post['slug'])
                ->withCanonical(route('frontend.posts.view', ['post' => $this->post['slug']]))
                ->withPreviousPost($this->findPreviousPost())
                ->withNextPost($this->findNextPost())
                ->withPost($this->post)
                ->withBreadcrumbs([
                    ['url' => route('frontend.blog'), 'display' => 'Web Development Blog'],
                    ['url' => '#', 'display' => $this->post['title']]
                ]);
        } catch (\Exception $e) {
            abort(Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Display blog posts for the given category.
     *
     * @param string $category
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function category($category)
    {
        abort_unless(postCategoryExists($category), Response::HTTP_NOT_FOUND);

        $posts = postsByCategory($category);

        return view('frontend.posts.post-category')
            ->withCanonical(getPostCategoryLink($category))
            ->withPosts($posts)
            ->withCategory(postCategoryDisplay($category));
    }

    /**
     * Attempt to find the given blog post.
     *
     * @param string $slug
     * @return array|null
     */
    private function findPost(string $slug) : ?array
    {
        return $this->posts->where('slug', $slug)->first();
    }

    /**
     * Attempt to find the next post.
     *
     * @return array|null
     */
    private function findNextPost() : ?array
    {
        $id = $this->posts->where('id', '<', $this->post['id'])->max('id');

        return $id === null
            ? null
            : $this->posts->where('id', $id)->first();
    }

    /**
     * Attempt to find the previous post.
     *
     * @return array|null
     */
    private function findPreviousPost() : ?array
    {
        $id = $this->posts->where('id', '>', $this->post['id'])->min('id');

        return $id === null
            ? null
            : $this->posts->where('id', $id)->first();
    }
}

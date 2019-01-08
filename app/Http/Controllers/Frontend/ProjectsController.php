<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Response;

class ProjectsController extends BaseController
{
    /**
     * @var array
     */
    protected $projects;

    /**
     * @var array|null
     */
    protected $project;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $data = getProjects();

        $this->projects = collect(array_get($data, 'projects', []));
    }

    /**
     * Display the given project's page.
     *
     * @param string $project
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(string $project)
    {
        // attempt to find the project
        $this->project = $this->findProject($project);

        abort_unless((bool) $this->project, Response::HTTP_NOT_FOUND);

        try {
            return view('frontend.projects.' . $this->project['slug'])
                ->withCanonical(route('frontend.projects.view', ['project' => $this->project['slug']]))
                ->withProject($this->project)
                ->withNextProject($this->findNextProject())
                ->withPreviousProject($this->findPreviousProject())
                ->withBreadcrumbs([
                    ['url' => route('frontend.projects'), 'display' => 'Projects'],
                    ['url' => '#', 'display' => $this->project['title']]
                ]);
        } catch (\Exception $e) {
            abort(Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Attempt to find the given project.
     *
     * @param string $slug
     * @return array|null
     */
    private function findProject(string $slug) : ?array
    {
        return $this->projects->where('slug', $slug)->first();
    }

    /**
     * Find the next project to display.
     *
     * @return array|null
     */
    private function findNextProject() : ?array
    {
        $id = $this->projects->where('id', '>', $this->project['id'])->min('id');

        return $id === null
            ? null
            : $this->projects->where('id', $id)->first();
    }

    /**
     * Find the previous project to display.
     *
     * @return array|null
     */
    private function findPreviousProject() : ?array
    {
        $id = $this->projects->where('id', '<', $this->project['id'])->max('id');

        return $id === null
            ? null
            : $this->projects->where('id', $id)->first();
    }
}

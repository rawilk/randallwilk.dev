<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\BaseController;
use function getPosts;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PagesController extends BaseController
{
    /**
     * Attempt to view the given page.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function viewPage(Request $request)
    {
        $page = $request->segment(1);

        abort_unless($page, Response::HTTP_NOT_FOUND);

        $method = 'get' . str_replace(' ', '', ucwords(str_replace('-', ' ', $page))) . 'Data';
        $data = method_exists($this, $method)
            ? $this->$method()
            : [];

        try {
            return view("frontend.pages.{$page}")
                ->withCanonical(url($page))
                ->with($data);
        } catch (\Exception $e) {
            abort(Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Get all the stored projects.
     *
     * @return array
     */
    private function getProjectsData() : array
    {
        return getProjects();
    }

    /**
     * Get the contact page data.
     *
     * @return array
     */
    private function getContactData() : array
    {
        return [
            'showCallToAction' => false
        ];
    }

    /**
     * Get all the blog posts.
     *
     * @return array
     */
    private function getBlogData() : array
    {
        return getPosts();
    }

    /**
     * Get the data needed for the resume page.
     *
     * @return array
     */
    private function getResumeData() : array
    {
        return [
            'personalSkills' => [
                'Detail Oriented', 'Reliable', 'Flexible',
                'Personal Integrity', 'Organized', 'Adaptability',
            ],
            'professionalSkills' => [
                'skilled' => [
                    'Laravel', 'Vue', 'Linux', 'Ajax', 'Sass', 'MySQL',
                    'MVC', 'Cross-browser compatibility',
                    'Responsive layout & design', 'GIT',
                    'NPM', 'jQuery', 'WordPress'
                ],
                'familiar' => [
                    'ASP.NET', 'Adobe Photoshop', 'Adobe Illustrator',
                    'Microsoft Visual Studio'
                ]
            ],
            'languages' => [
                'PHP'                    => 95,
                'HTML5'                  => 95,
                'CSS3'                   => 90,
                'JavaScript'             => 95,
                'c#'                     => 60,
                'Microsoft Visual Basic' => 50
            ],
            'ntcDiplomas' => [
                'Database Programmer',
                'Microsoft.NET Programmer',
                'Software Development Specialist',
                'Software Project Manager',
                'User Experience',
                'Web Programmer'
            ]
        ];
    }
}

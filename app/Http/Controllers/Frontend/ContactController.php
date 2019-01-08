<?php

namespace App\Http\Controllers\Frontend;

use App\Events\NewContactSubmission;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Frontend\Contact\SendContactRequest;

class ContactController extends BaseController
{
    /**
     * Send a new contact email.
     *
     * @param \App\Http\Requests\Frontend\Contact\SendContactRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(SendContactRequest $request)
    {
        $results = ['sent' => true];
        $message = 'Your message has been received! I will be in touch shortly.';

        event(new NewContactSubmission($request->all()));

        return $this->sendSuccessResponse($message, $results);
    }
}

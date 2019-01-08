<?php

namespace App\Http\Requests\Frontend\Contact;

use App\Http\Requests\BaseRequest;

class SendContactRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'    => 'required|max:100',
            'email'   => 'required|email',
            'subject' => 'max:100',
            'message' => 'required|max:5000'
        ];
    }
}

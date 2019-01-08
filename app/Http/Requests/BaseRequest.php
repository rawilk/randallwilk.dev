<?php

namespace App\Http\Requests;

use App\Exceptions\GeneralException;
use App\Exceptions\ValidException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

/**
 * Base Request
 *
 * All form requests for the application should extends this class.
 */
abstract class BaseRequest extends FormRequest
{
    /**
     * The error message to use in a response for failed authorization.
     *
     * @var string
     */
    protected $error = '';

    /**
     * Determine if the user is authorized to make the request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * Handle a failed authorization attempt.
     *
     * @throws \App\Exceptions\GeneralException
     */
    public function failedAuthorization()
    {
        if (empty($this->error)) {
            $this->error = trans('requests.unauthorized');
        }

        if ($this->session()->has('temp_error_message')) {
            $this->error = $this->session()->get('temp_error_message');
        }

        throw GeneralException::because($this->error)
            ->withStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @throws \App\Exceptions\ValidException
     */
    public function failedValidation(Validator $validator)
    {
        throw new ValidException($validator);
    }
}

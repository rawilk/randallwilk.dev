<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator as ValidatorFacade;

/**
 * Valid Exception
 *
 * This exception will be responsible for rendering validation exceptions thrown
 * by the BaseRequest class.
 */
class ValidException extends Exception
{
    /**
     * The HTTP status code to use for the response.
     *
     * @var int
     */
    protected $status = Response::HTTP_UNPROCESSABLE_ENTITY;

    /**
     * The validator instance.
     *
     * @var \Illuminate\Contracts\Validation\Validator
     */
    protected $validator;

    /**
     * Create a new exception instance.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     */
    public function __construct(Validator $validator)
    {
        parent::__construct('');

        $this->validator = $validator;
    }

    /**
     * Create a new valid exception from a plain array of messages.
     *
     * @param array $messages
     * @return \App\Exceptions\ValidException
     */
    public static function fromMessages(array $messages) : self
    {
        return new static(tap(ValidatorFacade::make([], []), function (Validator $validator) use ($messages) {
            foreach ($messages as $key => $value) {
                foreach (Arr::wrap($value) as $message) {
                    $validator->errors()->add($key, $message);
                }
            }
        }));
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function render($request)
    {
        return $request->expectsJson()
            ? response()->json(['errors' => $this->errors()], $this->status)
            : back()->withInput($request->except('password', 'password_confirmation'))->withErrors($this->errors());
    }

    /**
     * Set the HTTP status code to use for the response.
     *
     * @param int $status
     * @return \App\Exceptions\ValidException
     */
    public function withStatus(int $status) : self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get all of the validation error messages.
     *
     * @return array
     */
    private function errors() : array
    {
        return $this->validator->errors()->messages();
    }
}

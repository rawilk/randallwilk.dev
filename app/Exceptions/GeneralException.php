<?php

namespace App\Exceptions;

use App\Enums\Requests\ResponseStatus;
use Exception;
use Illuminate\Http\Response;

/**
 * General Exception
 *
 * This class will be responsible for handling the majority of exceptions
 * thrown by the application.
 */
class GeneralException extends Exception
{
    /**
     * Any extra data to send with the response.
     * Only applies to AJAX requests.
     *
     * @var array
     */
    protected $data = [];

    /**
     * The key to use for the `message` in the response.
     * Only applies to AJAX requests.
     *
     * @var string
     */
    protected $messageKey = 'reason';

    /**
     * The HTTP status code to return in the response.
     *
     * @var int
     */
    protected $status = Response::HTTP_UNPROCESSABLE_ENTITY;

    /**
     * Create a new exception instance.
     *
     * @param string $message
     */
    public function __construct(string $message = '')
    {
        parent::__construct($message);
    }

    /**
     * Convenience method for throwing general exception.
     *
     * @param string $message
     * @return \App\Exceptions\GeneralException
     */
    public static function because(string $message = '') : self
    {
        return new static($message);
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function render($request)
    {
        if ($request->expectsJson()) {
            return response()->json($this->buildAjaxResponse(), $this->status);
        }

        return redirect()->to($this->getRedirectUrl())
            ->withInput($request->except('password', 'password_confirmation'))
            ->withFlashDanger($this->getMessage());
    }

    /**
     * Send extra data through with the response.
     *
     * @param array $data
     * @return \App\Exceptions\GeneralException
     */
    public function withData(array $data) : self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Set the message key for the HTTP response.
     *
     * @param string $messageKey
     * @return \App\Exceptions\GeneralException
     */
    public function withMessageKey(string $messageKey) : self
    {
        $this->messageKey = $messageKey;

        return $this;
    }

    /**
     * Set the HTTP status code to be used for the response.
     *
     * @param int $status
     * @return \App\Exceptions\GeneralException
     */
    public function withStatus(int $status) : self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Build an array of response data for an AJAX response.
     *
     * @return array
     */
    private function buildAjaxResponse() : array
    {
        return array_merge([
            'status'          => ResponseStatus::ERROR,
            $this->messageKey => $this->getMessage()
        ], $this->data);
    }

    /**
     * Get the appropriate url to redirect to.
     * Some endpoints can cause infinite loops.
     *
     * @return string
     */
    private function getRedirectUrl() : string
    {
        if (request()->is('password/reset/*')) {
            return route('auth.login');
        }

        return url()->previous();
    }
}

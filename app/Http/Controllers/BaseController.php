<?php

namespace App\Http\Controllers;

use App\Enums\Requests\ResponseStatus;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Send a successful response to the client.
     *
     * @param null|string $message
     * @param array $results
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendSuccessResponse(?string $message = '', array $results = [])
    {
        return response()->json([
            'status'  => ResponseStatus::SUCCESS,
            'message' => $message,
            'results' => $results
        ]);
    }
}

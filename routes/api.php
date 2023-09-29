<?php

declare(strict_types=1);

use App\Http\Controllers\Api\Webhooks\HandleGitHubRepositoryWebhookController;
use Illuminate\Support\Facades\Route;

Route::prefix('webhooks')->group(function () {
    Route::post('github', HandleGitHubRepositoryWebhookController::class);
});

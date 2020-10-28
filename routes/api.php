<?php

use App\Api\HandleGitHubWebhookController;
use Illuminate\Support\Facades\Route;

Route::prefix('webhooks')->group(static function () {
    Route::post('github', HandleGitHubWebhookController::class);
});

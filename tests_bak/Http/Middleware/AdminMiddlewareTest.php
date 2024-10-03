<?php

declare(strict_types=1);

use App\Http\Middleware\AdminMiddleware;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

beforeEach(function () {
    $this->middleware = new AdminMiddleware;
    $this->seed();
});

it('will pass when a user is a super admin', function () {
    $request = new Request;

    $request->setUserResolver(
        fn () => User::factory()->superAdmin()->create(),
    );

    $response = $this->middleware->handle($request, fn (Request $request) => $request);

    expect($response)->not()->toBeNull()
        ->and($response)->toBeInstanceOf(Request::class);
});

it('will deny guest users', function () {
    $request = new Request;

    expect(
        fn () => $this->middleware->handle($request, fn (Request $request) => $request)
    )->toThrow(NotFoundHttpException::class);
});

it('will deny non super admin users', function () {
    $request = new Request;

    $request->setUserResolver(
        fn () => User::factory()->make(),
    );

    expect(
        fn () => $this->middleware->handle($request, fn (Request $request) => $request)
    )->toThrow(NotFoundHttpException::class);
});

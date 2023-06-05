<?php

namespace App\Exceptions;

use App\Helpers\Response;
use App\Helpers\Telegram;
use App\Jobs\SendErrorToTelegram;
use App\Models\Configs;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function report(Throwable $e)
    {
        ReportHandle($e);
    }

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*') || $request->ajax()) {
                return Response::Unauthorized('Không có quyền truy cập đường dẫn này');
            }
            return response()->view('errors.404');
        });
        $this->renderable(function (ThrottleRequestsException $e, $request) {
            return Response::badRequest('Bạn đã thử quá nhiều lần. Vui lòng thử lại sau.');
        });
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}

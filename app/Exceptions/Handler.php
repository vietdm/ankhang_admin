<?php

namespace App\Exceptions;

use App\Helpers\Response;
use App\Helpers\Telegram;
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
        logger($e);

        if (Configs::getDouble('allow_put_telegram', false)) {
            $errorMgs = $e->getMessage();
            $mgs = <<<text
Có lỗi xảy ra!
====================
$errorMgs
text;

            Telegram::pushMgs($mgs, Telegram::CHAT_REPORT_BUG, Telegram::BOT_TOKEN_REPORT_BUG);
        }
    }

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return Response::Unauthorized('Không có quyền truy cập đường dẫn này');
            }
        });
        $this->renderable(function (ThrottleRequestsException $e, $request) {
            return Response::badRequest('Bạn đã thử quá nhiều lần. Vui lòng thử lại sau.');
        });
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}

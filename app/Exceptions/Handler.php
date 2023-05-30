<?php

namespace App\Exceptions;

use App\Helpers\Telegram;
use App\Models\Configs;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}

<?php

namespace App\Jobs;

use App\Helpers\Telegram;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendErrorToTelegram implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $errorMgs;

    /**
     * Create a new job instance.
     */
    public function __construct(string $error)
    {
        $this->errorMgs = $error;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $mgs = <<<text
Có lỗi xảy ra!
====================
$this->errorMgs
text;

        Telegram::pushMgs($mgs, Telegram::CHAT_REPORT_BUG, Telegram::BOT_TOKEN_REPORT_BUG);
    }
}

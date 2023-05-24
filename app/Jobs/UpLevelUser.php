<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class UpLevelUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $user_id = 0;

    /**
     * Create a new job instance.
     */
    public function __construct($id = 0)
    {
        $this->user_id = $id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Artisan::call('user:up-level --id=' . $this->user_id);
    }
}

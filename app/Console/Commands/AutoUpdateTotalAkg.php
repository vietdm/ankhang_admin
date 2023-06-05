<?php

namespace App\Console\Commands;

use App\Helpers\Format;
use App\Models\Configs;
use App\Models\TotalAkgLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AutoUpdateTotalAkg extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-update-total-akg';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto update total Akg value';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $totalAkg = 60000000;

        $akgLog = TotalAkgLog::select(DB::raw('SUM(amount) as total'))->first();
        $amountUsed = $akgLog->total;

        $akgPoint = $totalAkg - $amountUsed;

        Configs::set('total_akg', $akgPoint, Format::Double);
    }
}

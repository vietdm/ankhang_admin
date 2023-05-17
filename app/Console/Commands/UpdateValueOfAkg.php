<?php

namespace App\Console\Commands;

use App\Helpers\Format;
use App\Models\Configs;
use Illuminate\Console\Command;

class UpdateValueOfAkg extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'value-of-akg:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Value Of Akg';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $allowIncreaseValueOfAkg = Configs::get('allow_increase_value_of_akg', false, Format::Boolean);
        if (!$allowIncreaseValueOfAkg) return;
        $valueOfAkg = Configs::get('value_of_akg', 0, Format::Double);
        $newValueOfAkg = round($valueOfAkg + $valueOfAkg * 0.01); //0.01 = 1%
        Configs::set('value_of_akg', $newValueOfAkg, Format::Double);
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Banks;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class UpdateBankCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bank:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Bank List';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $client = new Client();
        $response = $client->request('GET', 'https://api.vietqr.io/v2/banks');
        $content = json_decode($response->getBody(), 1);
        DB::statement('TRUNCATE table banks;');
        DB::statement('ALTER table banks AUTO_INCREMENT = 1;');
        foreach ($content['data'] as $bank) {
            Banks::insert([
                "bin" => $bank['bin'],
                "code" => $bank['code'],
                "short_name" => $bank['short_name'],
                "name" => $bank['name'],
                "logo" => $bank['logo'],
                "swift_code" => $bank['swift_code'] ?? '',
            ]);
        }
    }
}

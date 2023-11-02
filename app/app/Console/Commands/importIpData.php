<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Networks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;

class importIpData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-ip-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = 'GeoLite2-Country-Blocks-IPv4.csv';

        $csv = Reader::createFromPath(Storage::disk('public')->path($filePath), 'r');
        $records = $csv->getRecords();

        $counter = 0;
        //array:6 [▼ // app/Http/Controllers/NetworksController.php:34
        //   0 => "network"
        //   1 => "geoname_id"
        //   2 => "registered_country_geoname_id"
        //   3 => "represented_country_geoname_id"
        //   4 => "is_anonymous_proxy"
        //   5 => "is_satellite_provider"
        // ]
        foreach ($records as $item) {
            if($counter > 0){
                $networkData = explode('/', $item[0]); // Split the network into address and netmask
                $networkAddress = $networkData[0];
                $netmask = $networkData[1];
                $geo_id = $item[1];
               
                $record = Networks::create([
                    'network_address' => inet_pton($networkAddress),
                    'netmask' =>  $netmask,
                    'country_code_id' => !empty($geo_id) ? $geo_id : null,
               ]);
            //    $this->output($record->id);
            //    Log::info($networkAddress);
            }
            $counter++;
        }        
    }
}

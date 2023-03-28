<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportAddressData extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:address_data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import province, district, ward data from csv file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DB::beginTransaction();
        try {

            $provincesImport = [];
            $districtsImport = [];
            $wardsImport = [];

            $provinces = $this->readCsvFile('province_vietnam.csv');
            $districts = $this->readCsvFile('districts_vietnam.csv');
            $wards = $this->readCsvFile('wards_vietnam.csv');

            foreach ($provinces as $province) {
                $provincesImport[] = [
                    'province_id' => $province[1],
                    'name' => $province[2],
                ];
            }
            DB::table('provinces')->insert($provincesImport);

            foreach ($districts as $district) {
                $districtsImport[] = [
                    'province_id' => $district[1],
                    'district_id' => $district[2],
                    'name' => $district[3],
                ];
            }
            DB::table('districts')->insert($districtsImport);

            foreach ($wards as $ward) {
                $wardsImport[] = [
                    'district_id' => $ward[1],
                    'name' => $ward[2],
                ];
            }
            DB::table('wards')->insert($wardsImport);

            DB::commit();
            return Command::SUCCESS;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error(__METHOD__ . ' - ' . __LINE__ . ' : ' . $e->getMessage());
            
            return Command::FAILURE;
        }
    }

    public function readCsvFile($name) {
        $data = [];
        $i = 0;
        $baseLink = storage_path('app/public/masterdata');
        if (($open = fopen($baseLink. "/" . $name, "r")) != false) {

            while (($row = fgetcsv($open)) !== false) {
                if ($i != 0) {
                    $data[] = $row;
                }
                $i++;
            }
        }

        return $data;
    }
}

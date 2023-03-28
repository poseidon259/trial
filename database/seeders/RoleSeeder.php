<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'name' => 'ADMIN'
            ],
            [
                'id' => 2,
                'name' => 'STORE'
            ],
            [
                'id' => 3,
                'name' => 'EMP'
            ],
            [
                'id' => 4,
                'name' => 'CUSTOMER'
            ],
        ];
        DB::table('roles')->insert($data);
    }
}

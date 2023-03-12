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
        DB::table('roles')->truncate();
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
                'name' => 'CUSTOMER'
            ],
        ];
        DB::table('roles')->insert($data);
    }
}

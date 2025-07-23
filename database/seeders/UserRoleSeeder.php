<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('user_roles')->insert([
            ['name' => 'admin', 'description' => 'Administrator'],
            ['name' => 'member', 'description' => 'Member'],
        ]);
    }
}

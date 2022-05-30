<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('statuses')->insert([
            'Name' => 'Paid',
        ]);
        DB::table('statuses')->insert([
            'Name' => 'OutStanding',
        ]);
        DB::table('statuses')->insert([
            'Name' => 'Overdue',
        ]);
    }
}

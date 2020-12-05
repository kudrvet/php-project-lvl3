<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory;

class DomainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        $data = [];
        for ($i = 0; $i < 5; $i++) {
            $data[$i]['name'] = normalizeUrl($faker->unique()->url);
            $data[$i]['created_at'] = $faker->dateTimeThisMonth;
            $data[$i]['updated_at'] = $faker->dateTimeThisMonth;
        }

        DB::table('domains')->insert($data);
    }
}

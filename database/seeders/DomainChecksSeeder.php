<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DomainChecksSeeder extends Seeder
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
        for ($i = 0; $i < 10; $i++) {
            $time = $faker->dateTimeThisMonth;
            $data[$i]['domain_id'] = rand(1, 5);
            $data[$i]['h1'] = $faker->word;
            $data[$i]['status_code'] = $faker->randomElement([200,null,503]);
            $data[$i]['keywords'] = $faker->sentence();
            $data[$i]['description'] = $faker->sentence();
            $data[$i]['created_at'] = $time;
            $data[$i]['updated_at'] = $time;
        }

        DB::table('domain_checks')->insert($data);
    }
}

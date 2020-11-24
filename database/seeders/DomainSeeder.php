<?php

namespace Database\Seeders;

use App\Models\Domain;
use App\Models\DomainCheck;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DomainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Domain::factory()->count(5)->create()->each(function ($domain) {
        DomainCheck::factory()->count(3)->create(['domain_id'=>$domain->id]);
        });
    }
}

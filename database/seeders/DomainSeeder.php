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
        Domain::factory()->count(3)->create()->each(function ($domain) {
            DomainCheck::factory()->count(1)->create(['domain_id'=>$domain->id]);
        });
    }
}

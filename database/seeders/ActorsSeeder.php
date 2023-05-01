<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Actor;
use Illuminate\Support\Facades\DB;

class ActorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Actor::factory()->count(500)->create();

        DB::transaction(function () {
            Actor::withoutEvents(function () {
                Actor::factory()->count(500)->create();
            });
        });
    }
}

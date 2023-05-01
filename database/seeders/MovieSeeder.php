<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Movie;
use Illuminate\Support\Facades\DB;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Movie::factory()->count(5000)->create();
        DB::transaction(function () {
            Movie::withoutEvents(function () {
                Movie::factory()->count(5000)->create();
            });
        });
    }
}
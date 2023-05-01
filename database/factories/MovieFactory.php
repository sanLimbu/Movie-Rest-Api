<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Genre;
use App\Models\Actor;
use App\Models\Movie;


use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'user_id' => User::factory(),
            'description' => $this->faker->paragraph,
            'image' => $this->faker->imageUrl,
            'release_date' => $this->faker->date,
            'rating' => $this->faker->word,
            'award_winning' => $this->faker->boolean,
        ];
    }

     // Define an after hook to attach roles to users
     public function configure()
     {
         return $this->afterCreating(function (Movie $movie) {
            $actors = Actor::inRandomOrder()->take(rand(1, 3))->get();
            $genres = Genre::inRandomOrder()->take(rand(1, 3))->get();
             $movie->actors()->attach($actors);
             $movie->genres()->attach($genres);
         });
     }

     public function withoutRelations()
     {
         $this->afterMaking(function (Movie $movie) {
             $movie->setRelations([]);
         })->afterCreating(function (Movie $movie) {
             $movie->setRelations([]);
         });
 
         return $this;
     }

}

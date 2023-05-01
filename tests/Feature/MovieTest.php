<?php

use App\Http\Requests\MoviePostRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Movie;
use function Pest\Laravel\get;
use App\Models\User;
use App\Models\Actor;
use App\Models\Genre;


it('validates the movie name', function () {
    $validator = Validator::make([
        'name' => ''
    ], [
        'name' => 'required|string|max:255'
    ]);

    expect($validator->fails())->toBeTrue();

    $validator = Validator::make([
        'name' => 'A valid movie name'
    ], [
        'name' => 'required|string|max:255'
    ]);

    expect($validator->fails())->toBeFalse();
});

it('validates the movie description', function () {
    $validator = Validator::make([
        'description' => ''
    ], [
        'description' => 'required|string'
    ]);

    expect($validator->fails())->toBeTrue();

    $validator = Validator::make([
        'description' => 'A valid movie description'
    ], [
        'description' => 'required|string'
    ]);

    expect($validator->fails())->toBeFalse();
});

it('validates the movie', function () {
    $validator = Validator::make([
        'movie' => ''
    ], [
        'movie' => 'required|string'
    ]);

    expect($validator->fails())->toBeTrue();

    $validator = Validator::make([
        'movie' => 'A valid movie description'
    ], [
        'movie' => 'required|string'
    ]);

    expect($validator->fails())->toBeFalse();
});

it('validates the movie release date', function () {
    $validator = Validator::make([
        'release_date' => ''
    ], [
        'release_date' => 'required|date'
    ]);

    expect($validator->fails())->toBeTrue();

    $validator = Validator::make([
        'release_date' => '2022-04-30'
    ], [
        'release_date' => 'required|date'
    ]);

    expect($validator->fails())->toBeFalse();
});

it('validates the movie rating', function () {
    $validator = Validator::make([
        'rating' => ''
    ], [
        'rating' => 'required|numeric|min:0|max:10'
    ]);

    expect($validator->fails())->toBeTrue();

    $validator = Validator::make([
        'rating' => 8.5
    ], [
        'rating' => 'required|numeric|min:0|max:10'
    ]);

    expect($validator->fails())->toBeFalse();
});

test('it creates a new movie', function () {
    // create a user
    $user = User::factory()->create();

    // data for the movie creation
    $movieData = [
        'name' => 'Test Movie',
        'user_id' => $user->id,
        'description' => 'This is a test movie',
        'image' => 'https://test.com/image.jpg',
        'release_date' => '2022-05-01',
        'rating' => "good",
        'award_winning' => 1,
        'genres' => [1, 2],
        'actors' => [1, 2]
    ];
    
    // make a POST request to create the movie
    $response = $this->postJson(route('api.movies.store'), $movieData);
    
    // assert that the response is successful
    $response->assertSuccessful();
    
    // assert that the movie has been created
    $this->assertDatabaseHas('movies', [
        'name' => 'Test Movie',
        'user_id' => $user->id,
        'description' => 'This is a test movie',
        'image' => 'https://test.com/image.jpg',
        'release_date' => '2022-05-01',
        'rating' => "good",
        'award_winning' => 1
    ]);
    
    // assert that the genres have been attached to the movie
    $movie = Movie::where('name', 'Test Movie')->first();
    $this->assertEquals([1, 2], $movie->genres->pluck('id')->toArray());
    
    // assert that the actors have been attached to the movie
    $this->assertEquals([1, 2], $movie->actors->pluck('id')->toArray());
    });


it('displays a list of movies', function () {
    
    // Arrange
    // $movies = Movie::factory()->count(1)->create();
    $movies = Movie::factory()->create();
    // Act
    $response = get(route('api.movies.index'));
    // Assert
    $response
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'user',
                    'release_date',
                    'user_id',
                ],
            ],
        ]);
});


it('filters movies by release date', function () {
    // Arrange
    $movies = Movie::factory()->count(1)->create([
        'release_date' => '2020-01-01',
    ]);

    // Act
    $response = get(route('api.movies.index', ['release_date' => '2020-01-01']));

    // Assert
    $response
        ->assertOk()
        ->assertJsonFragment([
            'id' => $movies->first()->id,
        ]);
});


it('filters movies by genres', function () {
   
    // Arrange
    $horrorMovie  = Movie::factory()->create();
    $horrorMovie->genres()->attach(1);

    //Act
    $response = get(route('api.movies.index', ['genres' => 'Horror']));
    //Assert
    $response
        ->assertOk()
        ->assertJsonFragment([
             "genres" => ["Horror"]
        ]);
});



it('filters movies by actors', function () {
    // Arrange
    $actor1 = Actor::factory()->create(['name' => 'Actor 1']);
    $actor2 = Actor::factory()->create(['name' => 'Actor 2']);

    $movie1 = Movie::factory()->withoutRelations()->create(['name' => 'Movie 1']);
    $movie1->actors()->attach($actor1);

    $movie2 = Movie::factory()->withoutRelations()->create(['name' => 'Movie 2']);
    $movie2->actors()->attach($actor1);
    $movie2->actors()->attach($actor2);

    // Act
    $response = get(route('api.movies.index', ['actors' => 'Actor 1']));

    // Assert
    $response
        ->assertOk()
        ->assertJsonFragment([
            'name' => 'Movie 1',
            'actors' => [
                $actor1->name
            ],
        ])
        ->assertJsonFragment([
            'name' => 'Movie 2',
            'actors' => [
                $actor1->name,
                $actor2->name,
            ],
        ]);
});



test('update a movie', function () {
    $movie = Movie::factory()->create();

    $actor1 = Actor::factory()->create();
    $actor2 = Actor::factory()->create();

    $genre1 = Genre::create(['name' => 'Drama']);
    $genre2 = Genre::create(['name' => 'Comedy']);

    $newName = 'New Movie Name';
    $newReleaseDate = '2023-01-01';
    $newActors = [$actor1->id, $actor2->id];
    $newGenres = [$genre1->id, $genre2->id];

    $response = $this->putJson(route('api.movies.update', ['movie' => $movie->id]), [
        'name' => $newName,
        'release_date' => $newReleaseDate,
        'actors' => $newActors,
        'genres' => $newGenres,
    ]);

    $response->assertOk()
        ->assertJson([
            'message' => 'Movie updated successfully.',
            'movie' => [
                'id' => $movie->id,
                'name' => $newName,
                'release_date' => $newReleaseDate,
                'actors' => [
                   $actor1->name,
                   $actor2->name,
                ],
                'genres' => [
                    $genre1->name,
                    $genre2->name,
                ],
            ],
        ]);

});


it('deletes a movie', function () {
    // Create a movie to delete
    $movie = Movie::factory()->create();

    // Make a DELETE request to the movies endpoint with the movie ID
    $response = $this->delete(route('api.movies.destroy', ['id' => $movie->id]));

    // Assert that the response is successful
    $response->assertOk();

    // Assert that the movie has been deleted from the database
    $this->assertDatabaseMissing('movies', ['id' => $movie->id]);
});




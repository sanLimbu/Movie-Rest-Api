<?php

namespace App\Http\Controllers;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\MoviePostRequest;
use App\Http\Requests\MovieUpdateRequest;

use App\Models\Genre;
use App\Models\Actor;
class MovieController extends Controller
{
    

    public function index(Request $request)
    {
        $movies = Movie::query();

        // Filter by release date
        if ($request->has('release_date')) {
            $movies->where('release_date', $request->release_date);
        }
    
           // Filter by genres
        if ($request->has('genres')) {
            $genres = explode(',', $request->input('genres')); 
            $genreIds = DB::table('genres')
                ->whereIn('name', $genres)
                ->pluck('id')
                ->toArray();

            $movies->whereHas('genres', function ($query) use ($genreIds) {
                $query->whereIn('genres.id', $genreIds);
            })->get();     
        }
   
           // Filter by actors
        if ($request->has('actors')) {

            $actors = explode(',', $request->input('actors')); 
            $actorIds = DB::table('actors')
                ->whereIn('name', $actors)
                ->pluck('id')
                ->toArray();

                $movies->whereHas('actors', function ($query) use ($actorIds) {
                $query->whereIn('actors.id', $actorIds);
            })->get();
        }
    
        $movies = $movies->with('user')->get();
        return MovieResource::collection($movies);
    }

    public function store(MoviePostRequest $request)
        {
               $movie = Movie::create([
                    'name'          => $request->input('name'),
                    'user_id'       => $request->input('user_id'),
                    'description'   => $request->input('description'),
                    'image'         => $request->input('image'),
                    'release_date'  => $request->input('release_date'),
                    'rating'        => $request->input('rating'),
                    'award_winning' => $request->input('award_winning'),
               ]);

                // Attach genres to the movie
                $genres = $request->input('genres');
                $movie->genres()->attach($genres);

                 // Attach actors to the movie
                $actors = $request->input('actors');
                $movie->actors()->attach($actors);

               return response()->json([
                'success' => true,
                'data' => [
                    'movie' => $movie,
                    'genres' => $movie->genres,
                    'actors' => $movie->actors,
                ],
            ]);
          
        }

        public function update(MovieUpdateRequest $request, Movie $movie)
        {
            
            $movie->update([
                'name'          => $request->input('name'),
                'release_date'  => $request->input('release_date'),
            ]);
        
                $actors = Actor::whereIn('id', $request->input('actors'))->get();
                $movie->actors()->detach();
                $movie->actors()->attach($actors);
        
                $genres = Genre::whereIn('id', $request->input('genres'))->get();
                $movie->genres()->detach();
                $movie->genres()->attach($genres);
      
        
            return response()->json([
                'message' => 'Movie updated successfully.',
                'movie' => new MovieResource($movie),
            ]);
        }    
        

        public function destroy($id)
        {
            $movie = Movie::find($id);
        
            if (!$movie) {
                return response()->json(['message' => 'Movie not found.'], 404);
            }
        
            $movie->actors()->detach();
            $movie->genres()->detach();
            $movie->delete();
        
            return response()->json(['message' => 'Movie deleted.'], 200);
        }   



}

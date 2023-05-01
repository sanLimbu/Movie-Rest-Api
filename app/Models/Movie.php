<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Genre;
use App\Models\Actor;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'description',
        'actor_id',
        'image',
        'release_date',
        'rating',
        'award_winning',
    ];

    /**
     * Get the user that created the movie.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the genres associated with the movie.
     */
    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    /**
     * Get the actors associated with the movie.
     */
    public function actors()
    {
        return $this->belongsToMany(Actor::class);
    }
}
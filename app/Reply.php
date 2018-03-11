<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $fillable = [
        'thread_id',
        'user_id',
        'body'
    ];

    //////////////////////////////////////// RELATIONSHIP ////////////////////////////////////////

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritables');
    }

    /////////////////////////////////////////// HELPER ///////////////////////////////////////////

    public function toggleFavorite()
    {
        $this->favorites()->updateOrCreate(['user_id' => auth()->id()]);
    }

    public function isFavorited()
    {
        return $this->favorites()->where('user_id', auth()->id())->exists();
    }
}

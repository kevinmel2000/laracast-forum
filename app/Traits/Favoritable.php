<?php

namespace App\Traits;

use App\Favorite;

trait Favoritable
{
    //////////////////////////////////////// RELATIONSHIP ////////////////////////////////////////

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
        // NOTE: Use Collection "favorites" item instead of Eloquent "favorites()" relationship,
        // thus we then use count() instead of exists()
        return $this->favorites->where('user_id', auth()->id())->count();
    }
}
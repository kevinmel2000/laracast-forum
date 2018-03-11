<?php

namespace App;

use App\Traits\Favoritable;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favoritable;

    protected $fillable = [
        'thread_id',
        'user_id',
        'body'
    ];

    protected $with = ['owner', 'favorites'];

    //////////////////////////////////////// RELATIONSHIP ////////////////////////////////////////

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

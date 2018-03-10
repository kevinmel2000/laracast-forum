<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'body'
    ];

    //////////////////////////////////////// RELATIONSHIP ////////////////////////////////////////

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    /////////////////////////////////////////// HELPER ///////////////////////////////////////////

    public function path()
    {
        return '/thread/' . $this->id;
    }

    public function addReply($reply)
    {
        $this->replies()->create($reply);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    protected $fillable = [
        'user_id',
        'channel_id',
        'title',
        'body'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('repliesCount', function ($builder) {
            $builder->withCount('replies');
        });
    }

    //////////////////////////////////////// QUERY SCOPE /////////////////////////////////////////

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    //////////////////////////////////////// RELATIONSHIP ////////////////////////////////////////

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class)
                    ->withCount('favorites'); // Count favorites using eager load
    }

    /////////////////////////////////////////// HELPER ///////////////////////////////////////////

    public function path()
    {
        return "/thread/{$this->channel->slug}/$this->id";
    }

    public function addReply($reply)
    {
        $this->replies()->create($reply);
    }
}

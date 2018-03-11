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
                    ->with('owner')           // Use eager load to reduce query load
                    ->withCount('favorites'); // Use eager load to reduce query load
    }

    /////////////////////////////////////////// HELPER ///////////////////////////////////////////

    public function path()
    {
        // NOTE: To avoid N+1 problem, eager load the 'channel' relationship
        // See ThreadController.php's getThreads() function for detail
        // This way we hunt down from 52 queries to only 2-3 queries

        return "/thread/{$this->channel->slug}/$this->id";
    }

    public function addReply($reply)
    {
        $this->replies()->create($reply);
    }
}

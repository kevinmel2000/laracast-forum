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

    protected $with = ['channel'];

    /////////////////////////////////////////// BOOT ////////////////////////////////////////////

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('repliesCount', function ($builder) {
            $builder->withCount('replies');
        });

        // NOTE: Yes we can use "protected $with = ['author']", but
        // the advantages of using global scope is we can disable it by calling withoutGlobalScopes()
        static::addGlobalScope('author', function ($builder) {
            $builder->with('author');
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
                    ->withCount('favorites'); 

                    // NOTE: Alternatively use Reply's protected $with method instead
                    // ->with('owner') 
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

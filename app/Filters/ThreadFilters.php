<?php

namespace App\Filters;

use App\User;

class ThreadFilters extends BaseFilter
{
    protected $filters = ['by'];
    
    /**
     * Filter a Thread query by a given username
     *
     * @param string $username
     * @return void
     */
    protected function by($username)
    {
        $user = User::where('name', $username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }
}
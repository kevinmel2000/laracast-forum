<?php

namespace App\Filters;

use App\User;

class ThreadFilters extends BaseFilter
{
    protected $filters = ['by', 'popular'];
    
    /**
     * Filter a thread query by a given username
     *
     * @param string $username
     * @return Builder
     */
    protected function by($username)
    {
        $user = User::where('name', $username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }

    /**
     * Filter a thread query by its replies count
     *
     * @return Builder
     */
    public function popular()
    {
        // When a popular filter exists, remove the latest() default filter
        $this->builder->getQuery()->orders = [];

        return $this->builder->orderBy('replies_count', 'desc');
    }
}
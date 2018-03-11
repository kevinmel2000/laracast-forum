<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Thread;

class ProfileTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_has_their_profile_page()
    {
        $user = create(User::class);

        $this->get("/profile/{$user->name}")
             ->assertSee($user->name);
    }

    /** @test */
    public function profile_displays_all_threads_posted_by_associated_user()
    {
        $user = create(User::class);

        $threadOne = create(Thread::class, ['user_id' => $user->id]);
        $threadTwo = create(Thread::class, ['user_id' => $user->id]);

        $this->get("/profile/{$user->name}")
             ->assertSee($threadOne->title)
             ->assertSee($threadTwo->title);
    }
}

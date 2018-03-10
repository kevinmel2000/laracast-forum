<?php

namespace Tests\Feature;

use App\User;
use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function guest_may_not_create_a_thread()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        
        $thread = factory(Thread::class)->make();

        $this->post('/thread', $thread->toArray());
    }

    /** @test */
    public function an_authenticated_user_can_create_a_thread()
    {
        // Given we have a signed in user
        $this->actingAs(factory(User::class)->create());

        // When we hit the endpoint to create a new thread
        $thread = factory(Thread::class)->make();
        $this->post('/thread', $thread->toArray());

        // Then, when we visit this thread page,...
        $this->get($thread->path())
             ->assertSee($thread->title)
             ->assertSee($thread->body);
    }
}

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
    public function a_guest_can_not_create_a_thread()
    {
        $this->withExceptionHandling();

        // Can not see a thread create form, and...
        $this->get('/thread/create')
             ->assertRedirect('/login');

        // ...can not store a new thread
        $this->post('/thread')
             ->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_can_create_a_thread()
    {
        // Given we have a signed in user
        $this->signIn();

        // When we hit the endpoint to create a new thread
        $thread = create(Thread::class);
        $this->post('/thread', $thread->toArray());

        // Then, when we visit this thread page,...
        $this->get($thread->path())
             ->assertSee($thread->title)
             ->assertSee($thread->body);
    }
}

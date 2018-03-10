<?php

namespace Tests\Feature;

use App\Thread;
use App\Channel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadTest extends TestCase
{
    use DatabaseMigrations;

    protected function publishThread($overrides = [])
    {
        $this->withExceptionHandling()
             ->signIn();

        $thread = make(Thread::class, $overrides);

        return $this->post('/thread', $thread->toArray());
    }

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
        $thread = make(Thread::class);
        
        $response = $this->post('/thread', $thread->toArray());

        // Then, when we visit this thread page,...
        $this->get($response->headers->get('Location'))
             ->assertSee($thread->title)
             ->assertSee($thread->body);
    }

    /** @test */
    public function a_thread_creation_requires_a_title()
    {
        $this->publishThread(['title' => null])
             ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_creation_requires_a_body()
    {
        $this->publishThread(['body' => null])
             ->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_thread_creation_requires_a_valid_channel()
    {
        create(Channel::class); // id=1
        
        $this->publishThread(['channel_id' => null])
             ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
             ->assertSessionHasErrors('channel_id');
    }
}

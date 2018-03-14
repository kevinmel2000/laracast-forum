<?php

namespace Tests\Feature;

use App\Reply;
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

        // Given: no signed in user
        // When: we go to thread creation URI
        // Then: we should redirected to the login page
        $this->get('/thread/create')
             ->assertRedirect('/login');

        // Given: no signed in user
        // When: we go to thread store URI
        // Then: we should redirected to the login page
        $this->post('/thread')
             ->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_can_create_a_thread()
    {
        // Given: we have a signed in user
        $this->signIn();

        // When: we hit the endpoint to create a new thread
        $thread = make(Thread::class);
        
        $response = $this->post('/thread', $thread->toArray());

        // Then: when we visit this thread page
        // we should see the newly created thread
        $this->get($response->headers->get('Location'))
             ->assertSee($thread->title)
             ->assertSee($thread->body);
    }

    /** @test */
    public function a_thread_creation_requires_a_title()
    {
        // Given: a signed in user 
        // When: this signed user create a thread with nulled title
        // Then: we should see a validation/session error on 'title'
        $this->publishThread(['title' => null])
             ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_creation_requires_a_body()
    {
        // Given: a signed in user 
        // When: this signed user create a thread with nulled body
        // Then: we should see a validation/session error on 'body'
        $this->publishThread(['body' => null])
             ->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_thread_creation_requires_a_valid_channel()
    {
        // Given: a channel (id=1)
        create(Channel::class);
        
        // When: we create a thread without a channel
        // Then: we should see a validation/session error on 'channel_id'
        $this->publishThread(['channel_id' => null])
             ->assertSessionHasErrors('channel_id');

        // When: we create a thread with a channel that does not exists
        // Then: we should see a validation/session error on 'channel_id'
        $this->publishThread(['channel_id' => 999])
             ->assertSessionHasErrors('channel_id');
    }

    /** @test */
    public function an_unauthorized_user_may_not_delete_a_thread()
    {
        $this->withExceptionHandling();

        // Given: 
        // 1. No user signed in
        // 2. A thread created
        $thread = create(Thread::class);

        // When: we try to delete that thread
        // Then: we should redirected to the login page (since it's unauthorized)
        $this->delete($thread->path())
             ->assertRedirect('/login');

        // Given: a signed in user
        $this->signIn();

        // When: we try to delete that thread (which does not belongs to the signed in user)
        // Then: we should redirect to the login page (since it's unauthorized)
        $this->delete($thread->path())
             ->assertStatus(403);
    }

    /** @test */
    public function an_authorized_user_can_delete_a_thread()
    {
        // Given: a signed user create a thread with a favorited reply
        $this->signIn();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);
        $reply = create(Reply::class, ['thread_id' => $thread->id]);
        $favorite = $reply->favorites()->create(['user_id' => auth()->id()]);

        // When: we hit a delete endpoint of this thread
        $response = $this->json('DELETE', $thread->path());

        // Then: 
        // 1. We should get 204 http code
        // 2. With no thread and its favorited reply on the DB
        $response->assertStatus(204);
        
        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertDatabaseMissing('favorites', ['favoritables_id' => $reply->id]);
    }

    /** @test */
    public function a_thread_can_only_be_deleted_by_those_who_have_permission()
    {
        //
    }
}

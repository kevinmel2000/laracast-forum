<?php

namespace Tests\Feature;

use App\User;
use App\Reply;
use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function an_unauthenticated_user_may_not_add_reply()
    {
        $this->withExceptionHandling()
             ->post('/thread/some-channel/1/reply', [])
             ->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_may_participate_in_a_thread()
    {
        $this->be($user = create(User::class));
        
        $thread = create(Thread::class);

        $reply = make(Reply::class, [
            'thread_id' => $thread->id,
            'user_id' => $user->id
        ]);

        $this->post($thread->path() . '/reply', $reply->toArray());

        $this->get($thread->path())
             ->assertSee($reply->body);
    }

    /** @test */
    public function a_reply_creation_requires_a_body()
    {
        $this->withExceptionHandling()
             ->signIn();

        $thread = create(Thread::class);
        $reply  = make(Reply::class, ['body' => null]);

        $this->post($thread->path() . '/reply', $reply->toArray())
             ->assertSessionHasErrors('body');
    }
}

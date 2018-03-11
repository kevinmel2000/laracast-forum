<?php

namespace Tests\Feature;

use App\User;
use App\Reply;
use App\Thread;
use App\Channel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    public function setUp()
    {
        parent::setUp();

        $this->thread = create(Thread::class);
    }

    /** @test */
    public function a_user_can_view_all_threads()
    {
        $this->get('/thread')
             ->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_read_a_single_thread()
    {
        $this->get($this->thread->path())
             ->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_read_replies_associated_with_a_thread()
    {
        $reply = create(Reply::class, ['thread_id' => $this->thread->id]);

        $this->get($this->thread->path())
             ->assertSee($reply->body);
    }

    /** @test */
    public function a_user_can_filter_threads_according_to_a_channel()
    {
        $channel = create(Channel::class);

        $threadInChannel = create(Thread::class, ['channel_id' => $channel->id]);
        $threadNotInChannel = create(Thread::class);

        $this->get('/thread/' . $channel->slug)
             ->assertSee($threadInChannel->title)
             ->assertDontSee($threadNotInChannel->title);
    }

    /** @test */
    public function a_user_can_filter_threads_by_any_username()
    {
        $this->signIn(create(User::class, ['name' => 'Kukuh']));

        $threadByKukuh = create(Thread::class, ['user_id' => auth()->id()]);
        $threadNotByKukuh = create(Thread::class);

        $this->get('/thread?by=' . auth()->user()->name)
             ->assertSee($threadByKukuh->title)
             ->assertDontSee($threadNotByKukuh->title);
    }

    /** @test */
    public function a_user_can_filter_thread_by_popularity()
    {
        // Given we have 3 threads
        // with 2 replies, 3 replies, and 0 reply respectively
        $threadWith2Replies = create(Thread::class);
        create(Reply::class, ['thread_id' => $threadWith2Replies->id], 2);

        $threadWith3Replies = create(Thread::class);
        create(Reply::class, ['thread_id' => $threadWith3Replies->id], 3);

        $threadWithNoReply = $this->thread;

        // When a user filter those threads by popularity
        $response = $this->getJson('/thread?popular=1')->json();

        // Then they should return from most replies to least.
        $this->assertEquals([3, 2, 0], array_column($response, 'replies_count'));
    }
}

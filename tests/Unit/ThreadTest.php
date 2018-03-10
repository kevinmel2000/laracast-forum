<?php

namespace Tests\Unit;

use App\User;
use App\Thread;
use App\Channel;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $this->thread = create(Thread::class);
    }

    /** @test */
    public function a_thread_can_make_a_string_path()
    {
        $thread = create(Thread::class);

        $this->assertEquals(
            "/thread/{$thread->channel->slug}/$thread->id", 
            $thread->path()
        );
    }

    /** @test */
    public function a_thread_has_an_author()
    {
        $this->assertInstanceOf(User::class, $this->thread->author);
    }

    /** @test */
    public function a_thread_belongs_to_a_channel()
    {
        $thread = create(Thread::class);

        $this->assertInstanceOf(Channel::class, $thread->channel);
    }

    /** @test */
    public function a_thread_has_a_replies()
    {
        $this->assertInstanceOf(Collection::class, $this->thread->replies);
    }

    /** @test */
    public function a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'user_id' => 1,
            'body' => 'Foo'
        ]);

        $this->assertCount(1, $this->thread->replies);
    }
}

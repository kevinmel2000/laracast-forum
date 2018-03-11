<?php

namespace Tests\Feature;

use App\Reply;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FavoritesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_guest_can_not_favorite_any_reply()
    {
        $this->withExceptionHandling();
        
        $this->post('/reply/1/favorite')
             ->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_can_favorite_any_reply()
    {
        $this->signIn();

        $reply = create(Reply::class);
        
        $this->post('/reply/' . $reply->id . '/favorite');

        $this->assertCount(1, $reply->favorites);
    }

    /** @test */
    public function an_authenticated_user_can_only_favorite_a_reply_once()
    {
        $this->signIn();

        $reply = create(Reply::class);
        
        $this->post('/reply/' . $reply->id . '/favorite'); // 1st: persisted
        $this->post('/reply/' . $reply->id . '/favorite'); // 2nd: should not persisted

        $this->assertCount(1, $reply->favorites);
    }
}

<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class CommentTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testCreateComment()
    {
        $admin = \App\User::find(1);
        $comment = factory('App\Comment')->make();

        

        $this->browse(function (Browser $browser) use ($admin, $comment) {
            $browser->loginAs($admin)
                ->visit(route('admin.comments.index'))
                ->clickLink('Add new')
                ->type("comment", $comment->comment)
                ->select("user_id", $comment->user_id)
                ->select("room_id", $comment->room_id)
                ->type("rate", $comment->rate)
                ->press('Save')
                ->assertRouteIs('admin.comments.index')
                ->assertSeeIn("tr:last-child td[field-key='comment']", $comment->comment)
                ->assertSeeIn("tr:last-child td[field-key='user']", $comment->user->name)
                ->assertSeeIn("tr:last-child td[field-key='room']", $comment->room->title)
                ->assertSeeIn("tr:last-child td[field-key='rate']", $comment->rate);
        });
    }

    public function testEditComment()
    {
        $admin = \App\User::find(1);
        $comment = factory('App\Comment')->create();
        $comment2 = factory('App\Comment')->make();

        

        $this->browse(function (Browser $browser) use ($admin, $comment, $comment2) {
            $browser->loginAs($admin)
                ->visit(route('admin.comments.index'))
                ->click('tr[data-entry-id="' . $comment->id . '"] .btn-info')
                ->type("comment", $comment2->comment)
                ->select("user_id", $comment2->user_id)
                ->select("room_id", $comment2->room_id)
                ->type("rate", $comment2->rate)
                ->press('Update')
                ->assertRouteIs('admin.comments.index')
                ->assertSeeIn("tr:last-child td[field-key='comment']", $comment2->comment)
                ->assertSeeIn("tr:last-child td[field-key='user']", $comment2->user->name)
                ->assertSeeIn("tr:last-child td[field-key='room']", $comment2->room->title)
                ->assertSeeIn("tr:last-child td[field-key='rate']", $comment2->rate);
        });
    }

    public function testShowComment()
    {
        $admin = \App\User::find(1);
        $comment = factory('App\Comment')->create();

        


        $this->browse(function (Browser $browser) use ($admin, $comment) {
            $browser->loginAs($admin)
                ->visit(route('admin.comments.index'))
                ->click('tr[data-entry-id="' . $comment->id . '"] .btn-primary')
                ->assertSeeIn("td[field-key='comment']", $comment->comment)
                ->assertSeeIn("td[field-key='user']", $comment->user->name)
                ->assertSeeIn("td[field-key='room']", $comment->room->title)
                ->assertSeeIn("td[field-key='rate']", $comment->rate);
        });
    }

}

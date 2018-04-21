<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class LikeTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testCreateLike()
    {
        $admin = \App\User::find(1);
        $like = factory('App\Like')->make();

        

        $this->browse(function (Browser $browser) use ($admin, $like) {
            $browser->loginAs($admin)
                ->visit(route('admin.likes.index'))
                ->clickLink('Add new')
                ->select("user_id", $like->user_id)
                ->select("room_id", $like->room_id)
                ->press('Save')
                ->assertRouteIs('admin.likes.index')
                ->assertSeeIn("tr:last-child td[field-key='user']", $like->user->name)
                ->assertSeeIn("tr:last-child td[field-key='room']", $like->room->title);
        });
    }

    public function testEditLike()
    {
        $admin = \App\User::find(1);
        $like = factory('App\Like')->create();
        $like2 = factory('App\Like')->make();

        

        $this->browse(function (Browser $browser) use ($admin, $like, $like2) {
            $browser->loginAs($admin)
                ->visit(route('admin.likes.index'))
                ->click('tr[data-entry-id="' . $like->id . '"] .btn-info')
                ->select("user_id", $like2->user_id)
                ->select("room_id", $like2->room_id)
                ->press('Update')
                ->assertRouteIs('admin.likes.index')
                ->assertSeeIn("tr:last-child td[field-key='user']", $like2->user->name)
                ->assertSeeIn("tr:last-child td[field-key='room']", $like2->room->title);
        });
    }

    public function testShowLike()
    {
        $admin = \App\User::find(1);
        $like = factory('App\Like')->create();

        


        $this->browse(function (Browser $browser) use ($admin, $like) {
            $browser->loginAs($admin)
                ->visit(route('admin.likes.index'))
                ->click('tr[data-entry-id="' . $like->id . '"] .btn-primary')
                ->assertSeeIn("td[field-key='user']", $like->user->name)
                ->assertSeeIn("td[field-key='room']", $like->room->title);
        });
    }

}

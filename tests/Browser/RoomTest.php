<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class RoomTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testCreateRoom()
    {
        $admin = \App\User::find(1);
        $room = factory('App\Room')->make();

        $relations = [
            factory('App\User')->create(), 
            factory('App\User')->create(), 
        ];

        $this->browse(function (Browser $browser) use ($admin, $room, $relations) {
            $browser->loginAs($admin)
                ->visit(route('admin.rooms.index'))
                ->clickLink('Add new')
                ->type("title", $room->title)
                ->type("info", $room->info)
                ->type("price", $room->price)
                ->type("max_tenants", $room->max_tenants)
                ->select('select[name="tenants[]"]', $relations[0]->id)
                ->select('select[name="tenants[]"]', $relations[1]->id)
                ->type("view_count", $room->view_count)
                ->radio("is_available", $room->is_available)
                ->press('Save')
                ->assertRouteIs('admin.rooms.index')
                ->assertSeeIn("tr:last-child td[field-key='title']", $room->title)
                ->assertSeeIn("tr:last-child td[field-key='info']", $room->info)
                ->assertSeeIn("tr:last-child td[field-key='price']", $room->price)
                ->assertSeeIn("tr:last-child td[field-key='max_tenants']", $room->max_tenants)
                ->assertSeeIn("tr:last-child td[field-key='tenants'] span:first-child", $relations[0]->name)
                ->assertSeeIn("tr:last-child td[field-key='tenants'] span:last-child", $relations[1]->name)
                ->assertSeeIn("tr:last-child td[field-key='view_count']", $room->view_count)
                ->assertSeeIn("tr:last-child td[field-key='is_available']", $room->is_available);
        });
    }

    public function testEditRoom()
    {
        $admin = \App\User::find(1);
        $room = factory('App\Room')->create();
        $room2 = factory('App\Room')->make();

        $relations = [
            factory('App\User')->create(), 
            factory('App\User')->create(), 
        ];

        $this->browse(function (Browser $browser) use ($admin, $room, $room2, $relations) {
            $browser->loginAs($admin)
                ->visit(route('admin.rooms.index'))
                ->click('tr[data-entry-id="' . $room->id . '"] .btn-info')
                ->type("title", $room2->title)
                ->type("info", $room2->info)
                ->type("price", $room2->price)
                ->type("max_tenants", $room2->max_tenants)
                ->select('select[name="tenants[]"]', $relations[0]->id)
                ->select('select[name="tenants[]"]', $relations[1]->id)
                ->type("view_count", $room2->view_count)
                ->radio("is_available", $room2->is_available)
                ->press('Update')
                ->assertRouteIs('admin.rooms.index')
                ->assertSeeIn("tr:last-child td[field-key='title']", $room2->title)
                ->assertSeeIn("tr:last-child td[field-key='info']", $room2->info)
                ->assertSeeIn("tr:last-child td[field-key='price']", $room2->price)
                ->assertSeeIn("tr:last-child td[field-key='max_tenants']", $room2->max_tenants)
                ->assertSeeIn("tr:last-child td[field-key='tenants'] span:first-child", $relations[0]->name)
                ->assertSeeIn("tr:last-child td[field-key='tenants'] span:last-child", $relations[1]->name)
                ->assertSeeIn("tr:last-child td[field-key='view_count']", $room2->view_count)
                ->assertSeeIn("tr:last-child td[field-key='is_available']", $room2->is_available);
        });
    }

    public function testShowRoom()
    {
        $admin = \App\User::find(1);
        $room = factory('App\Room')->create();

        $relations = [
            factory('App\User')->create(), 
            factory('App\User')->create(), 
        ];

        $room->tenants()->attach([$relations[0]->id, $relations[1]->id]);

        $this->browse(function (Browser $browser) use ($admin, $room, $relations) {
            $browser->loginAs($admin)
                ->visit(route('admin.rooms.index'))
                ->click('tr[data-entry-id="' . $room->id . '"] .btn-primary')
                ->assertSeeIn("td[field-key='title']", $room->title)
                ->assertSeeIn("td[field-key='info']", $room->info)
                ->assertSeeIn("td[field-key='price']", $room->price)
                ->assertSeeIn("td[field-key='max_tenants']", $room->max_tenants)
                ->assertSeeIn("tr:last-child td[field-key='tenants'] span:first-child", $relations[0]->name)
                ->assertSeeIn("tr:last-child td[field-key='tenants'] span:last-child", $relations[1]->name)
                ->assertSeeIn("td[field-key='view_count']", $room->view_count)
                ->assertSeeIn("td[field-key='is_available']", $room->is_available);
        });
    }

}

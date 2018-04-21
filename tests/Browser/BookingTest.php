<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class BookingTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testCreateBooking()
    {
        $admin = \App\User::find(1);
        $booking = factory('App\Booking')->make();

        

        $this->browse(function (Browser $browser) use ($admin, $booking) {
            $browser->loginAs($admin)
                ->visit(route('admin.bookings.index'))
                ->clickLink('Add new')
                ->select("room_id", $booking->room_id)
                ->select("user_id", $booking->user_id)
                ->type("date", $booking->date)
                ->radio("status", $booking->status)
                ->press('Save')
                ->assertRouteIs('admin.bookings.index')
                ->assertSeeIn("tr:last-child td[field-key='room']", $booking->room->title)
                ->assertSeeIn("tr:last-child td[field-key='user']", $booking->user->name)
                ->assertSeeIn("tr:last-child td[field-key='date']", $booking->date)
                ->assertSeeIn("tr:last-child td[field-key='status']", $booking->status);
        });
    }

    public function testEditBooking()
    {
        $admin = \App\User::find(1);
        $booking = factory('App\Booking')->create();
        $booking2 = factory('App\Booking')->make();

        

        $this->browse(function (Browser $browser) use ($admin, $booking, $booking2) {
            $browser->loginAs($admin)
                ->visit(route('admin.bookings.index'))
                ->click('tr[data-entry-id="' . $booking->id . '"] .btn-info')
                ->select("room_id", $booking2->room_id)
                ->select("user_id", $booking2->user_id)
                ->type("date", $booking2->date)
                ->radio("status", $booking2->status)
                ->press('Update')
                ->assertRouteIs('admin.bookings.index')
                ->assertSeeIn("tr:last-child td[field-key='room']", $booking2->room->title)
                ->assertSeeIn("tr:last-child td[field-key='user']", $booking2->user->name)
                ->assertSeeIn("tr:last-child td[field-key='date']", $booking2->date)
                ->assertSeeIn("tr:last-child td[field-key='status']", $booking2->status);
        });
    }

    public function testShowBooking()
    {
        $admin = \App\User::find(1);
        $booking = factory('App\Booking')->create();

        


        $this->browse(function (Browser $browser) use ($admin, $booking) {
            $browser->loginAs($admin)
                ->visit(route('admin.bookings.index'))
                ->click('tr[data-entry-id="' . $booking->id . '"] .btn-primary')
                ->assertSeeIn("td[field-key='room']", $booking->room->title)
                ->assertSeeIn("td[field-key='user']", $booking->user->name)
                ->assertSeeIn("td[field-key='date']", $booking->date)
                ->assertSeeIn("td[field-key='status']", $booking->status);
        });
    }

}

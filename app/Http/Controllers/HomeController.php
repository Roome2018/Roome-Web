<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $users = \App\User::latest()->limit(5)->get(); 
        $rooms = \App\Room::latest()->limit(5)->get(); 
        $bookings = \App\Booking::latest()->limit(5)->get(); 
        $comments = \App\Comment::latest()->limit(5)->get(); 

        return view('home', compact( 'users', 'rooms', 'bookings', 'comments' ));
    }
}

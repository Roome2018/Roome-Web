<?php

namespace App\Http\Controllers\Admin;

use App\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLikesRequest;
use App\Http\Requests\Admin\UpdateLikesRequest;

class LikesController extends Controller
{
    /**
     * Display a listing of Like.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('like_access')) {
            return abort(401);
        }


                $likes = Like::all();

        return view('admin.likes.index', compact('likes'));
    }

    /**
     * Show the form for creating new Like.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('like_create')) {
            return abort(401);
        }
        
        $users = \App\User::get()->pluck('name', 'id')->prepend(trans('global.app_please_select'), '');
        $rooms = \App\Room::get()->pluck('title', 'id')->prepend(trans('global.app_please_select'), '');

        return view('admin.likes.create', compact('users', 'rooms'));
    }

    /**
     * Store a newly created Like in storage.
     *
     * @param  \App\Http\Requests\StoreLikesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLikesRequest $request)
    {
        if (! Gate::allows('like_create')) {
            return abort(401);
        }
        $like = Like::create($request->all());



        return redirect()->route('admin.likes.index');
    }


    /**
     * Show the form for editing Like.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! Gate::allows('like_edit')) {
            return abort(401);
        }
        
        $users = \App\User::get()->pluck('name', 'id')->prepend(trans('global.app_please_select'), '');
        $rooms = \App\Room::get()->pluck('title', 'id')->prepend(trans('global.app_please_select'), '');

        $like = Like::findOrFail($id);

        return view('admin.likes.edit', compact('like', 'users', 'rooms'));
    }

    /**
     * Update Like in storage.
     *
     * @param  \App\Http\Requests\UpdateLikesRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLikesRequest $request, $id)
    {
        if (! Gate::allows('like_edit')) {
            return abort(401);
        }
        $like = Like::findOrFail($id);
        $like->update($request->all());



        return redirect()->route('admin.likes.index');
    }


    /**
     * Display Like.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! Gate::allows('like_view')) {
            return abort(401);
        }
        $like = Like::findOrFail($id);

        return view('admin.likes.show', compact('like'));
    }


    /**
     * Remove Like from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('like_delete')) {
            return abort(401);
        }
        $like = Like::findOrFail($id);
        $like->delete();

        return redirect()->route('admin.likes.index');
    }

    /**
     * Delete all selected Like at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('like_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Like::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }

}

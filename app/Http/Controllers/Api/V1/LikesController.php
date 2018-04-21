<?php

namespace App\Http\Controllers\Api\V1;

use App\Like;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLikesRequest;
use App\Http\Requests\Admin\UpdateLikesRequest;

class LikesController extends Controller
{
    public function index()
    {
        return Like::all();
    }

    public function show($id)
    {
        return Like::findOrFail($id);
    }

    public function update(UpdateLikesRequest $request, $id)
    {
        $like = Like::findOrFail($id);
        $like->update($request->all());
        

        return $like;
    }

    public function store(StoreLikesRequest $request)
    {
        $like = Like::create($request->all());
        

        return $like;
    }

    public function destroy($id)
    {
        $like = Like::findOrFail($id);
        $like->delete();
        return '';
    }
}

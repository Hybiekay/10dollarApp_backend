<?php

namespace App\Http\Controllers;

use App\Models\CommentModel;
use App\Http\Requests\StoreCommentModelRequest;
use App\Http\Requests\UpdateCommentModelRequest;

class CommentModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentModelRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CommentModel $commentModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentModelRequest $request, CommentModel $commentModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CommentModel $commentModel)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\MusicModel;
use App\Http\Requests\StoreMusicModelRequest;
use App\Http\Requests\UpdateMusicModelRequest;

class MusicModelController extends Controller
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
    public function store(StoreMusicModelRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(MusicModel $musicModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMusicModelRequest $request, MusicModel $musicModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MusicModel $musicModel)
    {
        //
    }
}

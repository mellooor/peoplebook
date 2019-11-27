<?php

namespace App\Http\Controllers;

use App\PlaceName;
use Illuminate\Http\Request;

class PlaceNamesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'place-name' => 'required|string|max:16777215'
        ]);

        $placeName = $request->input('place-name');

        // If the place has previously been added to the DB table, don't add it again.
        if (PlaceName::where('name', '=', $placeName)->count() > 0) {
            return redirect()->back()->with('fail', 'This place has already been added.');
        }

        $placeNameToAdd = new PlaceName();
        $placeNameToAdd->name = $placeName;

        if ($placeNameToAdd->save()) {
            return redirect()->back()->with('success', 'Place added.');
        } else {
            return redirect()->back()->with('fail', 'An error occurred when adding your place.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

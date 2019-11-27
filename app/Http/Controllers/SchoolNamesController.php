<?php

namespace App\Http\Controllers;

use App\SchoolName;
use Illuminate\Http\Request;

class SchoolNamesController extends Controller
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
           'school-name' => 'required|string|max:16777215'
        ]);

        $schoolName = $request->input('school-name');

        // If the school name has already been added to the DB table, don't add it again.
        if (SchoolName::where('name', '=', $schoolName)->count() > 0) {
            return redirect()->back()->with('fail', 'This school has already been added.');
        }

        $schoolNameToAdd = new SchoolName();
        $schoolNameToAdd->name = $schoolName;

        if ($schoolNameToAdd->save()) {
            return redirect()->back()->with('success', 'School added.');
        } else {
            return redirect()->back()->with('fail', 'An error occurred when adding your school.');
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

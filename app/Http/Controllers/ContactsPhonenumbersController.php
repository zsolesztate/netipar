<?php

namespace App\Http\Controllers;

use App\Models\ContactsPhonenumbers;
use Illuminate\Http\Request;

class ContactsPhonenumbersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ContactsPhonenumbers::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ContactsPhonenumbers $contactsPhonenumbers)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContactsPhonenumbers $contactsPhonenumbers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContactsPhonenumbers $contactsPhonenumbers)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactsPhonenumbers $contactsPhonenumbers)
    {
        //
    }
}

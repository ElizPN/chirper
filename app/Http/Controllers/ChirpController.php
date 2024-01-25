<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ChirpController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     */
// The index method  is responsible for retrieving a list of chirps 
// from the database and rendering them to the Chirps/Index 
     public function index(): Response 
    {
        return Inertia::render('Chirps/Index', [
            'chirps' => Chirp::with('user:id,name')->latest()->get(),

        ]);
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
    // we creates a new chirp object, 
    // sets its properties based on the validated user input, 
    // and saves it to the database, linking it to the current user.
    public function store(Request $request): RedirectResponse
    {
         $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);
 
        $request->user()->chirps()->create($validated);
 
        return redirect(route('chirps.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Chirp $chirp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chirp $chirp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chirp $chirp): RedirectResponse
    {
        // Laravel looks for a ChirpPolicy class and its update() method.
        // If a matching policy class and method exist, Laravel invokes the policy method, 
        // passing the current user object and the chirp object as arguments.
        $this->authorize('update', $chirp);
 
        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);
 
       $chirp->update($validated); 
 
        return redirect(route('chirps.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chirp $chirp): RedirectResponse
    {
        // This line checks if the currently logged-in user has the delete permission for the specified chirp $chirp. 
        // This ensures that only authorized users can delete chirps.
        $this->authorize('delete', $chirp);
 
        // delete comments from DB
        $chirp->delete();
 
        //redirects the user to the chirps index page (route('chirps.index')) 
        // after successfully deleting the chirp. 
        // This allows the user to see the updated list of chirps.
        return redirect(route('chirps.index'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
   
    public function index()
    {
        $users = User::with('roles')->get();
        return view('admin.utilisateurs.index',['users'=> $users]);
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.utilisateurs.create',['roles'=>$roles]);
    }

   
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required|exists:roles,name',
        ]);

         // Création de l’utilisateur
    $user = User::create([
        'name'     => $validated['name'],
        'email'    => $validated['email'],
        'password' => Hash::make($validated['password']),
    ]);

       // Attribution du rôle
       $user->assignRole($validated['role']);

       return redirect()->route('admin.utilisateurs.index')->with('success', 'Utilisateur créé avec succès.');
   
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $utilisateur)
    {
        $roles = Role::all();
        return view('admin.utilisateurs.edit',['utilisateur'=>$utilisateur,'roles'=>$roles]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $utilisateur)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $utilisateur->id,
            'role' => 'required|string',
        ]);

        $utilisateur->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $utilisateur->syncRoles([$request->role]); // on remplace le rôle unique

        return redirect()->route('admin.utilisateurs.index')
                        ->with('success', 'Utilisateur mis à jour avec son rôle.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $utilisateur)
    {
        $utilisateur->delete();

        return redirect()->route('admin.utilisateurs.index')
                        ->with('success', 'Utilisateur supprimer avec succes.');
 
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    public function index()
    {
        $folders = Folder::latest()->get();
        return view('admin.folders.index', compact('folders'));
    }

    public function create()
    {
        return view('admin.folders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:folders,name|min:3',
            'description' => 'nullable|string',
        ]);

        Folder::create($request->all());

        return redirect()->route('admin.folders.index')->with('success', 'Dossier créé avec succès.');
    }

    public function edit(Folder $folder)
    {
        return view('admin.folders.edit', compact('folder'));
    }

    public function update(Request $request, Folder $folder)
    {
        $request->validate([
            'name' => 'required|unique:folders,name,' . $folder->id,
            'description' => 'nullable|string',
        ]);

        $folder->update($request->all());

        return redirect()->route('admin.folders.index')->with('success', 'Dossier mis à jour.');
    }

    public function destroy(Folder $folder)
    {
        $folder->delete();
        return redirect()->route('admin.folders.index')->with('success', 'Dossier supprimé.');
    }
}

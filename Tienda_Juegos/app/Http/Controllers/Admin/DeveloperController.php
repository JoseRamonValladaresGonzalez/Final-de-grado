<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Developer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DeveloperController extends Controller
{
    public function index()
    {
        $developers = Developer::paginate(10);
        return view('admin.developers.index', compact('developers'));
    }

    public function create()
    {
        return view('admin.developers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo'        => 'nullable|image|max:2048',
        ]);

        if($request->hasFile('logo')){
            $data['logo'] = $request->file('logo')->store('developers','public');
        }

        Developer::create($data);

        return redirect()->route('admin.developers.index')
                         ->with('success','Desarrollador creado');
    }

    public function edit(Developer $developer)
    {
        return view('admin.developers.edit', compact('developer'));
    }

    public function update(Request $request, Developer $developer)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo'        => 'nullable|image|max:2048',
        ]);

        if($request->hasFile('logo')){
            Storage::disk('public')->delete($developer->logo);
            $data['logo'] = $request->file('logo')->store('developers','public');
        }

        $developer->update($data);

        return redirect()->route('admin.developers.index')
                         ->with('success','Desarrollador actualizado');
    }

    public function destroy(Developer $developer)
    {
        Storage::disk('public')->delete($developer->logo);
        $developer->delete();
        return redirect()->route('admin.developers.index')
                         ->with('success','Desarrollador eliminado');
    }
}

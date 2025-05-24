<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublisherController extends Controller
{
    public function index()
    {
        $publishers = Publisher::paginate(10);
        return view('admin.publishers.index', compact('publishers'));
    }

    public function create()
    {
        return view('admin.publishers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo'        => 'nullable|image|max:2048',
        ]);

        if($request->hasFile('logo')){
            $data['logo'] = $request->file('logo')->store('publishers','public');
        }

        Publisher::create($data);

        return redirect()->route('admin.publishers.index')
                         ->with('success','Editor creado');
    }

    public function edit(Publisher $publisher)
    {
        return view('admin.publishers.edit', compact('publisher'));
    }

    public function update(Request $request, Publisher $publisher)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo'        => 'nullable|image|max:2048',
        ]);

        if($request->hasFile('logo')){
            Storage::disk('public')->delete($publisher->logo);
            $data['logo'] = $request->file('logo')->store('publishers','public');
        }

        $publisher->update($data);

        return redirect()->route('admin.publishers.index')
                         ->with('success','Editor actualizado');
    }

    public function destroy(Publisher $publisher)
    {
        Storage::disk('public')->delete($publisher->logo);
        $publisher->delete();
        return redirect()->route('admin.publishers.index')
                         ->with('success','Editor eliminado');
    }
}

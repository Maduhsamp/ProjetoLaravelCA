<?php

namespace App\Http\Controllers;

use App\Models\Explorer;
use Illuminate\Http\Request;

class ExplorerController extends Controller
{
    public function index(Request $request)
    {
        $explorers = Explorer::all();
        return response()->json($explorers);
    }

    public function store(Request $request)
    {
        $explorer = Explorer::create($request->all());
        return response()->json($explorer, 201);
    }

    public function show($id)
    {
        $explorer = Explorer::findOrFail($id);
        return response()->json($explorer);
    }

    public function update(Request $request, $id)
    {
        $explorer = Explorer::findOrFail($id);
        $explorer->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude
        ]);

        return response()->json($explorer, 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Items;
use Illuminate\Http\Request;

class ItemsController extends Controller
{
    public function store(Request $request)
    {
        $item = Items::create($request->all());
        return response()->json($item, 201);
    }

    public function index(Request $request)
    {
        $items = Items::all();
        return response()->json($items);
    }

    public function show($id)
    {
        $item = Items::findOrFail($id);
        return response()->json($item);
    }
}

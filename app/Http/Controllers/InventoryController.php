<?php

namespace App\Http\Controllers;

use App\Models\Explorer;
use App\Models\Inventory;
use App\Models\Items;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Constraint\IsEmpty;

class InventoryController extends Controller
{
    public function store(Request $request)
    {
        $inventory = Inventory::create($request->all());
        return response()->json($inventory, 201);
    }

    public function index(Request $request)
    {
        $inventories = Inventory::all();
        return response()->json($inventories);
    }

    public function explorerInventory(Request $request, $id)
    {
        $inventories = Inventory::where('explorer_id', $id)
        ->get();
        return response()->json($inventories, 200);
    }

    public function trocarItens(Request $request)
    {
        $data = $request->validate([
            'explorador_origem_troca_id' => 'required|exists:explorers,id',
            'explorador_destino_troca_id' => 'required|exists:explorers,id',
            'item_origem' => 'required',
            'item_destino' => 'required',
        ]);

        DB::beginTransaction();

        $itens_origem = $data['item_origem'];
        $itens_destino = $data['item_destino'];

        $explorador_origem = Inventory::find($data['explorador_origem_troca_id']);
        $explorador_destino = Inventory::find($data['explorador_destino_troca_id']);

        $valor_origem = Items::whereIn('id', $itens_origem)->sum('valor');
        $valor_destino = Items::whereIn('id', $itens_destino)->sum('valor');

        if($valor_origem !== $valor_destino) {
            DB::rollBack();
            return response()->json(['Error' => 'Os valores dos itens não são equivalentes!'], 400);
        }

        Inventory::whereIn('item_id', $itens_origem)->update(['explorer_id' => $explorador_destino->id]);
        Inventory::whereIn('item_id', $itens_destino)->update(['explorer_id' => $explorador_origem->id]);

        DB::commit();
        return response()->json(['Troca de itens realizada com sucesso!'], 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Explorer;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $request->validate([
            'explorador_origem_troca_id' => 'required|exists:explorers,id',
            'explorador_destino_troca_id' => 'required|exists:explorers,id',
            'item_origem' => 'required',
            'item_destino' => 'required',
        ]);

        DB::beginTransaction();

        try {

            $exploradorOrigemTroca = Explorer::findOrFail($request->explorador_origem_troca_id);
            $exploradorDestinoTroca = Explorer::findOrFail($request->explorador_destino_troca_id);

            if ($this->verificarTrocaJusta($exploradorOrigemTroca, $exploradorDestinoTroca, $request->item_origem, $request->item_destino)) {

                $this->efetuarTroca($exploradorOrigemTroca, $exploradorDestinoTroca, $request->item_origem, $request->item_destino);

                DB::commit();

                return response()->json(['mensagem' => 'Troca de itens realizada com sucesso'], 200);

            } else {

                DB::rollBack();

                return response()->json(['mensagem' => 'A troca de itens não é justa'], 400);
            }
        } catch (\Exception $e) {

            DB::rollBack();
            return response()->json(['mensagem' => 'Erro ao processar a troca de itens: ' . $e->getMessage()], 500);
        }
    }

    private function verificarTrocaJusta($itemOrigem, $itemDestino)
    {
        return $itemOrigem->valor == $itemDestino->valor;
    }
}

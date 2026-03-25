<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataCenter;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class DataCenterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $datacenters = DataCenter::orderBy('name', 'asc')->get();

            return response()->json([
                'success' => true,
                'datacenters' => $datacenters
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro ao carregar data centers:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar data centers'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:data_centers,name'
            ], [
                'name.required' => 'O nome do data center é obrigatório',
                'name.string' => 'O nome deve ser um texto válido',
                'name.max' => 'O nome não pode ter mais que 255 caracteres',
                'name.unique' => 'Já existe um data center com este nome'
            ]);

            $datacenter = DataCenter::create([
                'name' => $request->name,
                'description' => $request->description
            ]);

            \Log::info('Data center criado com sucesso:', [
                'datacenter_id' => $datacenter->id,
                'name' => $datacenter->name,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data center criado com sucesso!',
                'datacenter' => $datacenter
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Erro ao criar data center:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DataCenter $datacenter): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:data_centers,name,' . $datacenter->id
            ], [
                'name.required' => 'O nome do data center é obrigatório',
                'name.string' => 'O nome deve ser um texto válido',
                'name.max' => 'O nome não pode ter mais que 255 caracteres',
                'name.unique' => 'Já existe um data center com este nome'
            ]);

            $datacenter->update([
                'name' => $request->name,
                'description' => $request->description
            ]);

            \Log::info('Data center atualizado com sucesso:', [
                'datacenter_id' => $datacenter->id,
                'old_name' => $datacenter->getOriginal('name'),
                'new_name' => $datacenter->name,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data center atualizado com sucesso!',
                'datacenter' => $datacenter
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Erro ao atualizar data center:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'datacenter_id' => $datacenter->id,
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DataCenter $datacenter): JsonResponse
    {
        try {
            // Verificar se existem cards associados
            $cardsCount = $datacenter->cards()->count();

            if ($cardsCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Não é possível excluir este data center pois ele possui {$cardsCount} card(s) associado(s). Remova os cards primeiro ou altere o data center deles."
                ], 400);
            }

            $datacenterName = $datacenter->name;
            $datacenter->delete();

            \Log::info('Data center excluído com sucesso:', [
                'datacenter_name' => $datacenterName,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data center excluído com sucesso!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro ao excluir data center:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'datacenter_id' => $datacenter->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }
}

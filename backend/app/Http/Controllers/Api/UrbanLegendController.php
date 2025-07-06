<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreUrbanLegendRequest;
use Illuminate\Http\Request;
use App\Models\UrbanLegend;


class UrbanLegendController extends Controller
{
    public function store(StoreUrbanLegendRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $validatedData['user_id'] = 1;
            $post = UrbanLegend::create($validatedData);

            return response()->json([
                'message' => 'Lenda criada com sucesso!',
                'data' => $post,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar lendas',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

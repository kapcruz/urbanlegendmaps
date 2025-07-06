<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreUrbanLegendRequest;
use Illuminate\Http\Request;
use App\Models\UrbanLegend;
use App\Models\User;

class UrbanLegendController extends Controller
{

    public function show(Request $request)
    {
        $legends = UrbanLegend::query();

        if ($request->has('country')) {
            $legends->where('country', $request->country);
        }
        if ($request->has('city')) {
            $legends->where('city', $request->city);
        }

        $legends = $legends->get();

        $filtered = $legends->map(function ($legend) {
            return [
                'title' => $legend->title,
                'description' => $legend->description,
                'latitude' => $legend->latitude,
                'longitude' => $legend->longitude,
                'country' => $legend->country,
                'city' => $legend->city
            ];
        });

        return response()->json($filtered);
    }
    public function store(StoreUrbanLegendRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $user = User::first();
            $validatedData['user_id'] = $user->id;

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

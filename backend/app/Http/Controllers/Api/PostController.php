<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use Illuminate\Http\Request;
use App\Models\UrbanLegend;


class PostController extends Controller
{

    public function store(StorePostRequest $request)
    {
        try {

            $post = UrbanLegend::create([
                'title' => $request->title,
                'description' => $request->description,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'country' => $request->country,
                'city' => $request->city,
                'user_id' => 1,
            ]);

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

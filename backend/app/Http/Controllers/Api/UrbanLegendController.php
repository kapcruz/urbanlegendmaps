<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUrbanLegendRequest;
use App\Http\Requests\DeleteUrbanLegendRequest;
use Illuminate\Http\Request;
use App\Models\UrbanLegend;
use App\Models\User;
use App\Http\Resources\UrbanLegendResource;
use App\Services\Interfaces\UrbanLegendServiceInterface;

class UrbanLegendController extends Controller
{
    public function __construct(protected UrbanLegendServiceInterface $service) {}

    public function show(Request $request)
    {
        $filters = $request->only(['country','city','uuid','slug']);
        $legends = $this->service->list($filters);

        return UrbanLegendResource::collection($legends);
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

        $legend = $this->service->create($r->validated());
        return (new UrbanLegendResource($legend))->response()->setStatusCode(201);
        
    }

    public function update(StoreUrbanLegendRequest $request, string $uuid)
    {
        try {

            $validatedData = $request->validated();

            $update = UrbanLegend::where('uuid', $uuid)->firstOrFail();
            $update->update($validatedData); 
            
            return response()->json([
                'message' => 'Lenda atualizada com sucesso!',
                'data' => $update,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar lendas',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function destroy(DeleteUrbanLegendRequest $request, string $uuid)
    {
        try {
           UrbanLegend::select(['uuid' => $uuid])->delete();

            return response()->json([
                'message' => 'Lenda deletada com sucesso!'
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar lendas',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}

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
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

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
            $legend = $this->service->create($request->validated());
        } catch (QueryException $e) {
            if ($e->getCode() === '23000' && str_contains($e->getMessage(), 'urban_legends_title_key_unique')) {
                throw ValidationException::withMessages([
                    'title' => ['There is already an urban legend with this title.'],
                ]);
            }
            throw $e;
        }

        return (new UrbanLegendResource($legend))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
        
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
        $this->service->delete($uuid);
        return response()->noContent();
    }

}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUrbanLegendRequest;
use App\Http\Requests\DeleteUrbanLegendRequest;
use App\Http\Requests\UpdateUrbanLegendRequest;
use Illuminate\Http\Request;
use App\Http\Resources\UrbanLegendResource;
use App\Services\Interfaces\UrbanLegendServiceInterface;
use Symfony\Component\HttpFoundation\Response;

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
        $legend = $this->service->create($request->validated());

        return (new UrbanLegendResource($legend))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
        
    }

    public function update(UpdateUrbanLegendRequest $request, string $uuid)
    {
        $legend = $this->service->update($uuid, $request->validated());
        return new UrbanLegendResource($legend);
    }


    public function destroy(DeleteUrbanLegendRequest $request, string $uuid)
    {
        $this->service->delete($uuid);
        return response()->noContent();
    }

}

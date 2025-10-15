<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\UrbanLegend;
use App\Models\User;
use App\Services\Interfaces\UrbanLegendServiceInterface;

class UrbanLegendService implements UrbanLegendServiceInterface
{
    public function __construct(
        protected UrbanLegend $model,
        protected User $userModel
    ) {}

    public function list(array $filters = [])
    {
        return $this->model->query()->filter($filters)->get();
    }

    public function find(string $uuid)
    {
        return $this->model->where('uuid', $uuid)->firstOrFail();
    }

    public function create(array $data)
    {
        $data['user_id'] = $this->userModel->firstOrFail()->id;

        return DB::transaction(function () use ($data) {
            return $this->model->create($data);
        });
    }

    public function update(string $uuid, array $data)
    {
        $legend = $this->find($uuid);

        DB::transaction(function () use ($legend, $data) {
            $legend->update($data);
        });

        return $legend;
    }

    public function delete(string $uuid): bool
    {
        $legend = $this->find($uuid); 
        
        return (bool) DB::transaction(function () use ($legend) {
            $legend->images()->delete();
            return $legend->delete();
        });
    }
}

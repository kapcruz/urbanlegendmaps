<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\UrbanLegend;
use App\Models\User;
use App\Services\Interfaces\UrbanLegendServiceInterface;
use Illuminate\Support\Arr;
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
        $data = Arr::except($data, ['slug']);

        $user = User::first();

        if ($user === null) {
            throw ValidationException::withMessages([
                'user_id' => ['Need create a user before creating an urban legend.'],
            ]);
        }

        $data['user_id'] = $user->id;

        return UrbanLegend::create($data);
    }

    public function update(string $uuid, array $data): UrbanLegend
    {
        $data = Arr::except($data, ['slug']);
        
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

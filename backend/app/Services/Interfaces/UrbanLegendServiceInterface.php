<?php

namespace App\Services\Interfaces;

interface UrbanLegendServiceInterface
{
    public function list(array $filters = []); 
    public function find(string $uuid);
    public function create(array $data);
    public function update(string $uuid, array $data);
    public function delete(string $uuid): bool;
}

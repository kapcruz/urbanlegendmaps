<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Image extends Model
{
    use HasFactory;
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    protected $fillable = [
        'uuid',
        'legend_id',
        'url',
        'description',
    ];

    public function legend()
    {
        return $this->belongsTo(UrbanLegend::class, 'legend_id');
    }
}

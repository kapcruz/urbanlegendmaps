<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class UrbanLegend extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $hidden = ['id', 'user_id'];
    protected $fillable = [
        'uuid', 'user_id', 'title', 'description', 'latitude', 'longitude', 'country', 'city',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

     public function user()
     {
         return $this->belongsTo(User::class);
     }

     public function images()
     {
         return $this->hasMany(Image::class, 'legend_id');
     }
}


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
        'uuid', 'user_id', 'title', 'description', 'latitude', 'longitude', 'country', 'city', 'slug'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        }); 

        static::saving(function ($model) {
            $model->uuid ??= (string) Str::uuid();

            if (blank($model->slug) && ! blank($model->title)) {
                $model->slug = static::makeUniqueSlug($model->title, $model->getKey());
            }
        });
    }

    // Generate a unique slug based on the title
    protected static function makeUniqueSlug(string $title, $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'item';
        $slug = $base;
        $i = 2;

        $query = static::withTrashed();
        if ($ignoreId) {
            $query->whereKeyNot($ignoreId);
        }

        while ($query->where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'legend_id');
    }

    //Scopes for filtering
    public function scopeFilter($query, array $filter = [])
    {
        if (!empty($filter['country'])) $query->where('country', $filter['country']);
        if (!empty($filter['city'])) $query->where('city', $filter['city']);
        if (!empty($filter['uuid'])) $query->where('uuid', $filter['uuid']);
        if (!empty($filter['slug'])) $query->where('slug', $filter['slug']);
        return $query;
    }
}


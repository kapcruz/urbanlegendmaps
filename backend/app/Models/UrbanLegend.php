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
        'uuid', 'user_id', 'title', 'title_key', 'description', 'latitude', 'longitude', 'country', 'city', 'slug'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
            $model->title_key = Str::slug((string)$model->title) ?: 'item';
        }); 

        static::saving(function ($model) {
            $model->uuid ??= (string) Str::uuid();

            if (blank($model->slug) && ! blank($model->title)) {
                $model->title_key = Str::slug((string)$model->title) ?: 'item';
                $model->slug = $model->makeUniqueSlug(
                    title: (string) $model->title
                );
            }
        });

        static::updating(function (self $model) {
            if ($model->isDirty('title') || empty($model->slug)) {
                $model->title_key = Str::slug((string)$model->title) ?: 'item';
                $model->slug = $model->makeUniqueSlug(
                    title: (string) $model->title,
                    ignoreId: $model->getKey()
                );
            }
        });

    }

    // Generate a unique slug based on the title
    protected function makeUniqueSlug(string $title, $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'item';

        $baseQuery = static::withTrashed()
            ->when($ignoreId !== null, fn ($q) => $q->whereKeyNot($ignoreId));

        if (!(clone $baseQuery)->where('slug', $base)->exists()) {
            return $base;
        }

        $similar = (clone $baseQuery)
            ->where('slug', 'LIKE', $base.'%')
            ->pluck('slug')
            ->all();

        $pattern = '/^' . preg_quote($base, '/') . '\-(\d+)$/';
        $max = 1;

        foreach ($similar as $s) {
            if ($s === $base) {
                $max = max($max, 1);
                continue;
            }
            if (preg_match($pattern, $s, $m) === 1) {
                $max = max($max, (int) $m[1]);
            }
        }

        return $base . '-' . ($max + 1);
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


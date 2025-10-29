<?php

namespace App\Rules;

use Closure;
use App\Models\UrbanLegend;
use Illuminate\Support\Str;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueLegendTitle implements ValidationRule
{

    public function __construct(private ?string $ignoreUuid = null) {}
      /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $key = Str::slug((string)$value) ?: 'item';
        
        $exists = UrbanLegend::query()
            ->when($this->ignoreUuid, fn ($q) => $q->where('uuid', '!=', $this->ignoreUuid))
            ->where('title_key', $key)
            ->exists();

        if ($exists) {
            $fail('There is already an urban legend with this title.');
        }
    }
}



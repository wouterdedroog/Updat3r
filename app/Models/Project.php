<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;

/**
 * The Project model
 * @mixin Builder
 * @package App\Models
 */
class Project extends Model
{

    use HasFactory;

    protected $fillable = ['name', 'user_id', 'api_key', 'legacy_api_key'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function updates(): HasMany {
        return $this->hasMany(Update::class);
    }
}

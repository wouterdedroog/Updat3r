<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

/**
 * The Update model
 * @mixin Builder
 * @package App\Models
 */
class Update extends Model
{

    use HasFactory;

    protected $fillable = ['project_id', 'version', 'critical', 'public', 'filename'];

    public function project(): BelongsTo {
        return $this->belongsTo(Project::class);
    }
}

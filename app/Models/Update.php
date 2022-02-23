<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Update extends Model
{

    use HasFactory;

    protected $fillable = ['project_id', 'version', 'critical', 'public', 'filename'];

    public function project() {
        return $this->belongsTo(Project::class);
    }
}

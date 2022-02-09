<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Update extends Model
{

    protected $fillable = ['project_id', 'version', 'critical', 'public', 'filename'];

    public function project() {
        return $this->belongsTo(Project::class);
    }
}

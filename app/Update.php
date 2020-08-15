<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Update extends Model
{

    protected $fillable = ['project_id', 'version', 'critical', 'public', 'filename'];
    public function project() {
        return $this->belongsTo('App\Project');
    }
}

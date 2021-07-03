<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{

    protected $fillable = ['name', 'user_id', 'api_key', 'legacy_api_key'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function updates() {
        return $this->hasMany(Update::class);
    }
}

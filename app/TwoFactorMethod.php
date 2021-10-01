<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TwoFactorMethod extends Model
{

    protected $fillable = ['user_id', 'google2fa_secret', 'enabled'];
    protected $hidden = ['google2fa_secret'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}

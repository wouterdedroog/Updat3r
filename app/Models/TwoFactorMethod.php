<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TwoFactorMethod extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id', 'google2fa_secret', 'yubikey_otp', 'enabled'];
    protected $hidden = ['google2fa_secret', 'yubikey_otp'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}

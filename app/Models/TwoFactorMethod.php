<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

/**
 * The TwoFactorMethod model
 * @mixin Builder
 * @package App\Models
 */
class TwoFactorMethod extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id', 'google2fa_secret', 'yubikey_otp', 'enabled'];
    protected $hidden = ['google2fa_secret', 'yubikey_otp'];
    protected $casts = [
        'google2fa_secret' => 'encrypted',
        'yubikey_otp' => 'encrypted',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace Database\Factories;

use App\Models\TwoFactorMethod;
use Illuminate\Database\Eloquent\Factories\Factory;
use PragmaRX\Google2FAQRCode\Google2FA;

/**
 * @extends Factory<TwoFactorMethod>
 */
class TwoFactorMethodFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TwoFactorMethod::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $google2fa = new Google2FA();
        return [
            'name' => $this->faker->word(),
            'google2fa_secret' => encrypt($google2fa->generateSecretKey(32)),
            'enabled' => true
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return Factory
     */
    public function enabled()
    {
        return $this->state(function (array $attributes) {
            return [
                'enabled' => true,
            ];
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('two_factor_methods', function (Blueprint $table) {
            $table->string('yubikey_otp', 256)->after('google2fa_secret')->nullable();
            $table->string('google2fa_secret', 512)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('two_factor_methods', function (Blueprint $table) {
            $table->dropColumn('yubikey_otp');
            $table->string('google2fa_secret', 512)->change();
        });
    }
};

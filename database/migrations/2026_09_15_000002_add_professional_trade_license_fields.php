<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trade_licenses', function (Blueprint $table) {
            $table->string('owner_email')->nullable();
            $table->string('tin_no')->nullable();
            $table->string('bin_no')->nullable();
            $table->text('biz_permanent_address')->nullable();
            $table->text('biz_present_address')->nullable();
            $table->string('employee_count')->nullable();
            $table->string('signboard_size')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('trade_licenses', function (Blueprint $table) {
            $table->dropColumn([
                'owner_email', 'tin_no', 'bin_no', 'biz_permanent_address',
                'biz_present_address', 'employee_count', 'signboard_size'
            ]);
        });
    }
};

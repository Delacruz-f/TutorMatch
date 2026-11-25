<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('notificaciones') || Schema::hasColumn('notificaciones', 'tipo')) {
            return;
        }

        Schema::table('notificaciones', function (Blueprint $table) {
            $table->string('tipo', 30)->nullable()->after('user_id')->index();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('notificaciones') || ! Schema::hasColumn('notificaciones', 'tipo')) {
            return;
        }

        Schema::table('notificaciones', function (Blueprint $table) {
            $table->dropIndex(['tipo']);
            $table->dropColumn('tipo');
        });
    }
};

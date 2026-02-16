<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username', 50)->nullable()->after('id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
        });
    }
};

// use Illuminate\Database\Migrations\Migration;
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Support\Facades\Schema;

// return new class extends Migration
// {
//     public function up(): void
//     {
//         Schema::table('users', function (Blueprint $table) {
//             $table->string('username', 50)->nullable()->unique()->after('id');
//             $table->string('role', 20)->default('user')->after('password');
//             $table->text('permissions')->nullable()->after('role');
//         });
//     }

//     public function down(): void
//     {
//         Schema::table('users', function (Blueprint $table) {
//             $table->dropColumn(['username', 'role', 'permissions']);
//         });
//     }
// };

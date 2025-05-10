<?php

namespace Modules\Specialties\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First, remove the enum constraint by changing to string temporarily
        Schema::table('categories', function (Blueprint $table) {
            $table->string('status')->change();
        });

        // Convert values
        DB::table('categories')->where('status', 'active')->update(['status' => '1']);
        DB::table('categories')->where('status', 'inactive')->update(['status' => '0']);

        // Finally change to boolean
        Schema::table('categories', function (Blueprint $table) {
            $table->boolean('status')->default(true)->change();
        });
    }

    public function down(): void
    {
        // First change to string to remove boolean constraint
        Schema::table('categories', function (Blueprint $table) {
            $table->string('status')->change();
        });

        // Convert back to enum values
        DB::table('categories')->where('status', '1')->update(['status' => 'active']);
        DB::table('categories')->where('status', '0')->update(['status' => 'inactive']);

        // Finally restore the enum
        Schema::table('categories', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active')->change();
        });
    }
};

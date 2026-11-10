<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("CREATE INDEX events_dress_code_idx ON events ((details->>'dress_code'))");
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS events_dress_code_idx');
    }
};
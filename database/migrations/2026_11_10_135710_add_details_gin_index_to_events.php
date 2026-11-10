<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('CREATE INDEX events_details_gin_idx ON events USING GIN (details)');
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS events_details_gin_idx');
    }
};
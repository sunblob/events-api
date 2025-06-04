<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    // Меняем тип поля content с json на text
    DB::statement('ALTER TABLE pages MODIFY content TEXT NULL');
  }

  public function down(): void
  {
    // Возвращаем тип поля content обратно в json
    DB::statement('ALTER TABLE pages MODIFY content JSON NULL');
  }
};
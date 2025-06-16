<?php

namespace Ganesh\MigrationGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class GenerateMigrationsCommand extends Command
{
    protected $signature = 'migration:generate {table?}';
    protected $description = 'Generate migrations for an existing database';

    public function handle()
    {
        $tableName = $this->argument('table');
        $excludedTables = Config::get('migration-generator.exclude_tables', []);

        // Fetch table names
        $tables = $tableName ? [$tableName] : $this->getAllTables();

        foreach ($tables as $table) {
            if ($this->shouldExcludeTable($table, $excludedTables)) {
                $this->warn("Skipping excluded table: $table");
                continue;
            }

            if ($this->migrationExists($table)) {
                $this->warn("Skipping existing migration: $table");
                continue;
            }

            $this->info("Generating migration for: $table");
            $this->generateMigration($table);
        }

        $this->info('Migration generation completed.');
    }

    private function getAllTables()
    {
        $databaseName = config('database.connections.mysql.database');
        $tables = DB::select("SHOW TABLES");
        $tableKey = "Tables_in_{$databaseName}";

        return array_column($tables, $tableKey);
    }

    private function shouldExcludeTable($tableName, $excludedTables)
    {
        foreach ($excludedTables as $excludedPattern) {
            $pattern = "/^" . str_replace('\*', '.*', preg_quote($excludedPattern, '/')) . "$/";
            if (preg_match($pattern, $tableName)) {
                return true;
            }
        }
        return false;
    }

    private function migrationExists($tableName)
    {
        $migrationsPath = database_path('migrations');
        $files = scandir($migrationsPath);

        foreach ($files as $file) {
            if (strpos($file, "create_{$tableName}_table") !== false) {
                return true;
            }
        }

        return false;
    }

    private function generateMigration($tableName)
    {
        $className = 'Create' . Str::studly($tableName) . 'Table';
        $fileName = date('Y_m_d_His') . "_create_{$tableName}_table.php";
        $filePath = database_path("migrations/{$fileName}");

        $stub = $this->getStub();
        $migrationContent = str_replace(
            ['{{className}}', '{{tableName}}'],
            [$className, $tableName],
            $stub
        );

        file_put_contents($filePath, $migrationContent);

        // Insert into migrations table
        DB::table('migrations')->insert([
            'migration' => str_replace('.php', '', $fileName),
            'batch' => DB::table('migrations')->max('batch') + 1, // Increment batch number
        ]);

        $this->info("Migration created and recorded: $fileName");
    }

    private function getStub()
    {
        return <<<EOT
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('{{tableName}}', function (Blueprint \$table) {
            \$table->id();
            // Define columns manually
            \$table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('{{tableName}}');
    }
};
EOT;
    }
}

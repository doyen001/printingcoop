<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Data Migration from CodeIgniter to Laravel
 * 
 * This migration copies all data from the CI database to Laravel database
 * while preserving exact IDs, values, and data integrity.
 * 
 * IMPORTANT: 
 * - Run this AFTER all table migrations are complete
 * - Configure CI database connection in config/database.php as 'codeigniter'
 * - Verify data counts after migration
 */
class MigrateDataFromCodeigniter extends Migration
{
    /**
     * CI database connection name
     */
    private $ciConnection = 'codeigniter';
    
    /**
     * Laravel database connection name
     */
    private $laravelConnection = 'mysql';
    
    /**
     * Tables to migrate in order (respecting foreign keys)
     */
    private $tablesToMigrate = [
        // Core tables (no dependencies)
        'menus',
        'countries',
        'currencies',
        'stores',
        'admins',
        'users',
        
        // Location tables
        'states',
        'cities',
        
        // Category tables
        'categories',
        'sub_categories',
        'tags',
        
        // Attribute tables
        'attributes',
        'attribute_items',
        
        // Product tables
        'products',
        'product_images',
        'product_quantities',
        'product_sizes',
        'product_size_multiple_attributes',
        'product_attributes',
        'product_attribute_items',
        
        // Provider tables
        'providers',
        'provider_products',
        
        // Printer tables
        'printer_brands',
        'printer_series',
        'printers',
        
        // User related tables
        'addresses',
        'wishlists',
        'email_subscriptions',
        
        // Order tables
        'product_orders',
        'product_order_items',
        'order_files',
        
        // Discount tables
        'coupons',
        'discounts',
        
        // CMS tables
        'pages',
        'page_categories',
        'blogs',
        'blog_categories',
        'banners',
        'sections',
        'services',
        
        // Support tables
        'tickets',
        'ticket_categories',
        'ticket_replies',
        'supports',
        
        // Sales tax tables
        'sales_tax_rates_provinces',
        
        // Payment tables
        'payment_methods',
        
        // Configuration tables
        'configurations',
    ];
    
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->command->info('Starting data migration from CodeIgniter to Laravel...');
        
        // Verify CI connection
        if (!$this->verifyCIConnection()) {
            $this->command->error('Cannot connect to CodeIgniter database. Please configure the connection.');
            return;
        }
        
        // Disable foreign key checks
        DB::connection($this->laravelConnection)->statement('SET FOREIGN_KEY_CHECKS=0;');
        
        $totalTables = count($this->tablesToMigrate);
        $migratedTables = 0;
        
        foreach ($this->tablesToMigrate as $table) {
            $migratedTables++;
            $this->command->info("[$migratedTables/$totalTables] Migrating table: $table");
            
            try {
                $this->migrateTable($table);
            } catch (\Exception $e) {
                $this->command->warn("Skipping table $table: " . $e->getMessage());
                continue;
            }
        }
        
        // Re-enable foreign key checks
        DB::connection($this->laravelConnection)->statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // Verify data integrity
        $this->verifyDataIntegrity();
        
        $this->command->info('Data migration completed successfully!');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->command->warn('Data migration rollback is not supported.');
        $this->command->warn('Please restore from backup if needed.');
    }
    
    /**
     * Verify CI database connection
     */
    private function verifyCIConnection(): bool
    {
        try {
            DB::connection($this->ciConnection)->getPdo();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Migrate a single table
     */
    private function migrateTable(string $table): void
    {
        // Check if table exists in CI database
        if (!$this->tableExistsInCI($table)) {
            throw new \Exception("Table does not exist in CI database");
        }
        
        // Check if table exists in Laravel database
        if (!Schema::connection($this->laravelConnection)->hasTable($table)) {
            throw new \Exception("Table does not exist in Laravel database");
        }
        
        // Get data from CI database
        $data = DB::connection($this->ciConnection)
            ->table($table)
            ->get()
            ->map(function ($item) {
                return (array) $item;
            })
            ->toArray();
        
        if (empty($data)) {
            $this->command->info("  → No data to migrate for table: $table");
            return;
        }
        
        $count = count($data);
        $this->command->info("  → Found $count records");
        
        // Clear existing data in Laravel database
        DB::connection($this->laravelConnection)->table($table)->truncate();
        
        // Process data in chunks to avoid memory issues
        $chunks = array_chunk($data, 500);
        $processed = 0;
        
        foreach ($chunks as $chunk) {
            // Process each record to handle JSON columns and timestamps
            $processedChunk = array_map(function ($record) use ($table) {
                return $this->processRecord($record, $table);
            }, $chunk);
            
            // Insert data preserving IDs
            DB::connection($this->laravelConnection)
                ->table($table)
                ->insert($processedChunk);
            
            $processed += count($chunk);
            $this->command->info("  → Migrated $processed/$count records");
        }
        
        // Reset auto-increment to max ID + 1
        $this->resetAutoIncrement($table);
        
        $this->command->info("  ✓ Completed migration for table: $table");
    }
    
    /**
     * Check if table exists in CI database
     */
    private function tableExistsInCI(string $table): bool
    {
        try {
            DB::connection($this->ciConnection)->table($table)->limit(1)->get();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Process a single record
     */
    private function processRecord(array $record, string $table): array
    {
        // Handle JSON columns
        $jsonColumns = $this->getJsonColumns($table);
        foreach ($jsonColumns as $column) {
            if (isset($record[$column]) && !empty($record[$column])) {
                // If it's already a JSON string, keep it
                if (is_string($record[$column])) {
                    // Validate JSON
                    json_decode($record[$column]);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        // Not valid JSON, encode it
                        $record[$column] = json_encode($record[$column]);
                    }
                } else {
                    // Convert to JSON
                    $record[$column] = json_encode($record[$column]);
                }
            }
        }
        
        // Handle timestamp columns
        $timestampColumns = ['created', 'updated', 'deleted_at', 'created_at', 'updated_at'];
        foreach ($timestampColumns as $column) {
            if (isset($record[$column])) {
                // Ensure proper timestamp format
                if ($record[$column] === '0000-00-00 00:00:00') {
                    $record[$column] = null;
                }
            }
        }
        
        // Handle boolean columns (CI stores as tinyint)
        $booleanColumns = $this->getBooleanColumns($table);
        foreach ($booleanColumns as $column) {
            if (isset($record[$column])) {
                $record[$column] = (int) $record[$column];
            }
        }
        
        return $record;
    }
    
    /**
     * Get JSON columns for a table
     */
    private function getJsonColumns(string $table): array
    {
        $jsonColumns = [
            'products' => ['specifications', 'meta_data'],
            'product_orders' => ['shipping_info', 'billing_info'],
            'providers' => ['product_list', 'api_config'],
            'configurations' => ['settings'],
        ];
        
        return $jsonColumns[$table] ?? [];
    }
    
    /**
     * Get boolean columns for a table
     */
    private function getBooleanColumns(string $table): array
    {
        $booleanColumns = [
            'products' => ['status', 'featured', 'is_new'],
            'users' => ['status', 'email_verified', 'is_preferred_customer'],
            'admins' => ['status'],
            'categories' => ['status'],
            'sub_categories' => ['status'],
            'product_orders' => ['status'],
            'coupons' => ['status'],
        ];
        
        return $booleanColumns[$table] ?? [];
    }
    
    /**
     * Reset auto-increment for a table
     */
    private function resetAutoIncrement(string $table): void
    {
        try {
            $maxId = DB::connection($this->laravelConnection)
                ->table($table)
                ->max('id');
            
            if ($maxId) {
                $nextId = $maxId + 1;
                DB::connection($this->laravelConnection)
                    ->statement("ALTER TABLE `$table` AUTO_INCREMENT = $nextId");
            }
        } catch (\Exception $e) {
            // Table might not have an id column
        }
    }
    
    /**
     * Verify data integrity after migration
     */
    private function verifyDataIntegrity(): void
    {
        $this->command->info('Verifying data integrity...');
        
        $mismatches = [];
        
        foreach ($this->tablesToMigrate as $table) {
            if (!$this->tableExistsInCI($table)) {
                continue;
            }
            
            if (!Schema::connection($this->laravelConnection)->hasTable($table)) {
                continue;
            }
            
            $ciCount = DB::connection($this->ciConnection)->table($table)->count();
            $laravelCount = DB::connection($this->laravelConnection)->table($table)->count();
            
            if ($ciCount !== $laravelCount) {
                $mismatches[] = [
                    'table' => $table,
                    'ci_count' => $ciCount,
                    'laravel_count' => $laravelCount,
                    'difference' => $ciCount - $laravelCount,
                ];
            }
        }
        
        if (empty($mismatches)) {
            $this->command->info('✓ All table counts match!');
        } else {
            $this->command->warn('⚠ Found mismatches in the following tables:');
            foreach ($mismatches as $mismatch) {
                $this->command->warn(sprintf(
                    '  %s: CI=%d, Laravel=%d (diff: %d)',
                    $mismatch['table'],
                    $mismatch['ci_count'],
                    $mismatch['laravel_count'],
                    $mismatch['difference']
                ));
            }
        }
        
        // Display summary
        $this->displayMigrationSummary();
    }
    
    /**
     * Display migration summary
     */
    private function displayMigrationSummary(): void
    {
        $this->command->info('');
        $this->command->info('=== Migration Summary ===');
        
        $totalRecords = 0;
        
        foreach ($this->tablesToMigrate as $table) {
            if (!Schema::connection($this->laravelConnection)->hasTable($table)) {
                continue;
            }
            
            $count = DB::connection($this->laravelConnection)->table($table)->count();
            $totalRecords += $count;
            
            if ($count > 0) {
                $this->command->info(sprintf('  %-30s: %d records', $table, $count));
            }
        }
        
        $this->command->info('');
        $this->command->info("Total records migrated: $totalRecords");
        $this->command->info('=========================');
    }
}

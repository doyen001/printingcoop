<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearViewCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:clear-views';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear cached view composer data (pages, categories, configurations)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $patterns = [
            'pages_*',
            'categories_menu_*',
            'footer_categories_*',
            'configrations_*',
        ];

        foreach ($patterns as $pattern) {
            // For database cache, we need to delete by pattern
            if (config('cache.default') === 'database') {
                \DB::table('cache')
                    ->where('key', 'like', str_replace('*', '%', $pattern))
                    ->delete();
            } else {
                // For other cache drivers, try to forget common keys
                for ($i = 1; $i <= 10; $i++) {
                    Cache::forget(str_replace('*', $i, $pattern));
                }
            }
        }

        $this->info('View composer cache cleared successfully!');
        return 0;
    }
}

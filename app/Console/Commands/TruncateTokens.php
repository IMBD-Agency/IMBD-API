<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TruncateTokens extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'truncate_tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Turncate personal token';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        DB::table('personal_access_tokens')->truncate();
    }
}

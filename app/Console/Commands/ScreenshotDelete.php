<?php

namespace App\Console\Commands;

use App\Models\Screenshot;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ScreenshotDelete extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'screenshot_delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete previous screenshot';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        $screenshots = Screenshot::whereYear('created_at', date('Y'))->whereMonth('created_at', Carbon::now()->subMonth()->format('m'))->get();

        foreach ($screenshots as $screenshot) {
            if (unlink(public_path('/uploads/screenshot/' . $screenshot->image))) {
                $screenshot->delete();
                echo 'Deleted ' . $screenshot->image . '\n';
            }
        }
    }
}

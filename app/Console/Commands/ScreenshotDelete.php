<?php

namespace App\Console\Commands;

use App\Models\Screenshot;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

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
        $last_month = Carbon::now()->subMonth();
        $screenshots = Screenshot::where('created_at', '<=',  $last_month)->get();
        // $screenshots = Screenshot::whereYear('created_at', date('Y'))->whereMonth('created_at', Carbon::now()->subMonth()->format('m'))->get();
        foreach ($screenshots as $screenshot) {
            if (Storage::disk('sftp')->exists($screenshot->image)) {
                Storage::disk('sftp')->delete($screenshot->image);
                $screenshot->delete();
                echo "Deleted " . $screenshot->image . "\n";
            }
        }
    }
}

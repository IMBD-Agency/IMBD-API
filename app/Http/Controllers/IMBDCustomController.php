<?php

namespace App\Http\Controllers;

use App\Models\Screenshot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class IMBDCustomController extends Controller {
    public function delete_screenshot() {
        $last_month = Carbon::now()->subDays(30);
        $screenshots = Screenshot::whereDate('created_at', '<=',  $last_month)->get();
        // $screenshots = Screenshot::whereYear('created_at', date('Y'))->whereMonth('created_at', Carbon::now()->subMonth()->format('m'))->get();
        foreach ($screenshots as $screenshot) {
            if (Storage::disk('sftp')->exists($screenshot->image)) {
                Storage::disk('sftp')->delete($screenshot->image);
            }
            $screenshot->delete();
            echo "Deleted " . $screenshot->image . "\n";
        }
    }

    public function debug() {
        Log::debug('Here I am');
    }
}

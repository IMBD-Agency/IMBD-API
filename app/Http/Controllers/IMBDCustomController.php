<?php

namespace App\Http\Controllers;

use App\Models\Screenshot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IMBDCustomController extends Controller {
    public function delete_screenshot() {
        $screenshots = Screenshot::whereYear('created_at', date('Y'))->whereMonth('created_at', Carbon::now()->subMonth()->format('m'))->get();
        foreach ($screenshots as $screenshot) {
            if (Storage::disk('sftp')->exists($screenshot->image)) {
                Storage::disk('sftp')->delete($screenshot->image);
                $screenshot->delete();
                echo 'Deleted ' . $screenshot->image;
            }
        }
    }

    public function debug() {
    }
}

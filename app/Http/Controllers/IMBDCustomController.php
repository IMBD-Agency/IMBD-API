<?php

namespace App\Http\Controllers;

use App\Models\Screenshot;
use Carbon\Carbon;
use Illuminate\Http\Request;

class IMBDCustomController extends Controller {
    public function debug() {
        $screenshots = Screenshot::whereYear('created_at', date('Y'))->whereMonth('created_at', Carbon::now()->subMonth()->format('m'))->get();

        foreach ($screenshots as $screenshot) {
            if (unlink(public_path('/uploads/screenshot/' . $screenshot->image))) {
                $screenshot->delete();
                echo 'Deleted ' . $screenshot->image;
            }
        }
    }
}

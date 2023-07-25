<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\ActivityStoreRequest;
use App\Http\Requests\API\V1\ScreenshotStoreRequest;
use App\Models\EmployeeActivity;
use App\Models\Screenshot;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Image;

class EmployeeTrackingController extends Controller {
    public function screenshots_store(ScreenshotStoreRequest $request) {
        $file_name = Str::lower('screenshot-' . auth()->user()->id . '-' . uniqid() . '.jpg');
        if (Image::make($request->image)->resize('1920', '1080')->blur(25)->save(public_path('/uploads/screenshot/' . $file_name))) {
            Screenshot::create([
                'user_id' => auth()->user()->id,
                'screen_id' => $request->screen_id,
                'image' => $file_name,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Screenshot uploaded Successfully.'
            ], 201);
        }
    }

    public function activity_store(ActivityStoreRequest $request) {
        EmployeeActivity::create([
            'user_id' => auth()->user()->id,
            'activity' => $request->activity,
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Data uploaded Successfully.'
        ], 201);
    }
}

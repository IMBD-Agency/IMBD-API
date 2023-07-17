<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\AppActivityStoreRequest;
use App\Http\Requests\API\ScreenshotStoreRequest;
use App\Http\Requests\API\WebsiteActivityStoreRequest;
use App\Models\AppActivity;
use App\Models\Screenshot;
use App\Models\User;
use App\Models\WebsiteActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Image;

class EmployeeTrackingController extends Controller {
    public function index() {
        return auth()->user();
    }

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

    public function app_activity_store(AppActivityStoreRequest $request) {
        AppActivity::create([
            'user_id' => auth()->user()->id,
            'app_name' => $request->app_name,
            'duration' => $request->duration,
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Data uploaded Successfully.'
        ], 201);
    }

    public function website_activity_store(WebsiteActivityStoreRequest $request) {
        WebsiteActivity::create([
            'user_id' => auth()->user()->id,
            'url' => $request->url,
            'duration' => $request->duration,
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Data uploaded Successfully.'
        ], 201);
    }
}

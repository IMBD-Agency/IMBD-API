<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\ScreenshotStoreRequest;
use App\Http\Requests\API\V1\TimeTracingStoreRequest;
use App\Models\EmployeeActivity;
use App\Models\Screenshot;
use App\Models\TimeTracker;
use App\Models\TrackerSettings;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Image;

class EmployeeTrackingController extends Controller {

    public function settings() {
        Log::info('Someone access settings.');
        $data = TrackerSettings::first();
        return response()->json([
            'ScreenshotInterval' => $data->screenshot_interval,
            'ActivityInterval' => $data->activity_interval,
            'IdleTimeInterval' => $data->idle_time_interval
        ], 200);
    }

    public function screenshots_store(ScreenshotStoreRequest $request) {
        $file_name = Str::lower('screenshot-' . auth()->user()->id . '-' . uniqid() . '.jpg');
        $image = Image::make($request->image)->resize('1280', '720');
        if (Storage::disk('sftp')->put($file_name, $image->stream())) {
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

    public function activity_store(Request $request) {
        $datas = json_decode($request->getContent(), true);
        if ($datas) {
            foreach ($datas as $data) {
                $data = json_decode(json_encode($data));
                if ($data->name) {
                    $old_data = EmployeeActivity::where('user_id', auth()->user()->id)->whereDate('created_at', Carbon::today())->where('name', $data->name)->first();
                    if ($old_data) {
                        $old_data->update([
                            'duration' => $old_data->duration + $data->duration,
                        ]);
                    } else {
                        EmployeeActivity::create([
                            'user_id' => auth()->user()->id,
                            'name' => $data->name,
                            'duration' => $data->duration,
                        ]);
                    }
                } elseif ($data->url && preg_match('/^(http|https):\\/\\/[a-z0-9]+([\\-\\.]{1}[a-z0-9]+)*\\.[a-z]{2,5}' . '((:[0-9]{1,5})?\\/.*)?$/i', 'https://' . $data->url)) {
                    $pieces = parse_url('https://' . $data->url);
                    $domain = isset($pieces['host']) ?  $pieces['host'] : $pieces['path'];
                    $domain = 'https://' . $domain;
                    $old_data = EmployeeActivity::where('user_id', auth()->user()->id)->whereDate('created_at', Carbon::today())->where('url', $domain)->first();
                    if ($old_data) {
                        $old_data->update([
                            'duration' => $old_data->duration + $data->duration,
                        ]);
                    } else {
                        EmployeeActivity::create([
                            'user_id' => auth()->user()->id,
                            'url' => $domain,
                            'duration' => $data->duration,
                        ]);
                    }
                }
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Data uploaded Successfully.'
            ], 201);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Data structure is invalid.'
            ], 200);
        }
    }

    public function time_store(TimeTracingStoreRequest $request) {
        if ($request->total_time > 0) {
            $old_data = TimeTracker::where('user_id', auth()->user()->id)->whereDate('for_date', Carbon::today())->first();
            if ($old_data) {
                $data = $old_data->update([
                    'total_time' => $old_data->total_time + $request->total_time,
                    'active_time' => $old_data->active_time + $request->active_time,
                    'idle_time' => $old_data->idle_time + $request->idle_time,
                ]);
                $message = 'Data marged Successfully.';
            } else {
                $data = TimeTracker::create([
                    'user_id' => auth()->user()->id,
                    'total_time' => $request->total_time,
                    'active_time' => $request->active_time,
                    'idle_time' => $request->idle_time,
                    'for_date' => Carbon::today(),
                ]);

                $message = 'Data uploaded Successfully.';
            }

            if ($data) {
                $user = User::find(auth()->user()->id);
                $user->last_sync = now();
                $user->save();

                return response()->json([
                    'status' => 'success',
                    'message' => $message
                ], 201);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Something went wrong.'
                ], 200);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => "Can't insert null value."
            ], 200);
        }
    }
}

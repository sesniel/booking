<?php

namespace App\Http\Controllers;

use App\AppSetting;
use Illuminate\Http\Request;

class AppSettingsController extends Controller
{
    public function index()
    {
        return response()->json();
    }

    public function show(AppSetting $appSetting)
    {
        return response()->json($appSetting);
    }

    public function edit(AppSetting $appSetting)
    {
        return response()->json($appSetting);
    }
}

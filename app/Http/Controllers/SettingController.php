<?php

namespace App\Http\Controllers;

use App\Settings;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function getWhiteLogo(Request $request)
    {
        $whitelogo = Settings::where('field', Settings::WHITE_LOGO_FIELD)->first();
        if ($whitelogo) {
            return response()->json([
                'response' => [
                    'code' => 200,
                    'api_status' => 1,
                    Settings::WHITE_LOGO_FIELD => $whitelogo['value'],
                ],
            ]);
        }
        return response()->json([
            'response' => [
                'code' => 200,
                'api_status' => 1,
                Settings::WHITE_LOGO_FIELD => '/logos/LogoWhite.png',
            ],
        ]);
    }

    public function getBlackLogo(Request $request)
    {
        $blacklogo = Settings::where('field', Settings::BLACK_LOGO_FIELD)->first();
        if ($blacklogo) {
            return response()->json([
                'response' => [
                    'code' => 200,
                    'api_status' => 1,
                    Settings::BLACK_LOGO_FIELD => $blacklogo['value'],
                ],
            ]);
        }
        return response()->json([
            'response' => [
                'code' => 200,
                'api_status' => 1,
                Settings::BLACK_LOGO_FIELD => '/logos/LogoBlack.png',
            ],
        ]);
    }

    public function setWhiteLogo(Request $request)
    {
        $file = $request->file(Settings::WHITE_LOGO_FIELD);
        $destinationPath = 'logos';
        $filename = "Logo_" . date('Y-m-d_H-i-s') . "." . $file->getClientOriginalExtension();
        $type = Settings::LOGO_SETTING;
        try {
            $file->move($destinationPath, $filename);
            $whitelogo = Settings::where('field', Settings::WHITE_LOGO_FIELD)->first();
            if ($whitelogo) {
                $whitelogo['value'] = "/logos/$filename";
                $whitelogo->save();
            } else {
                Settings::create(['field' => Settings::WHITE_LOGO_FIELD, 'value' => "/logos/$filename", 'type' => $type]);
            }
            return response()->json([
                'response' => [
                    'code' => 200,
                    'api_status' => 1,
                    'filename' => "/logos/$filename",
                ],
            ]);
        } catch (Exception $error) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "Upload failed",
                ],
            ]);
        }
    }

    public function setBlackLogo(Request $request)
    {
        $file = $request->file(Settings::BLACK_LOGO_FIELD);
        $destinationPath = 'logos';
        $filename = "Logo_" . date('Y-m-d_H-i-s') . "." . $file->getClientOriginalExtension();
        $type = Settings::LOGO_SETTING;
        try {
            $file->move($destinationPath, $filename);
            $blacklogo = Settings::where('field', Settings::BLACK_LOGO_FIELD)->first();
            if ($blacklogo) {
                $blacklogo['value'] = "/logos/$filename";
                $blacklogo->save();
            } else {
                Settings::create(['field' => Settings::BLACK_LOGO_FIELD, 'value' => "/logos/$filename", 'type' => $type]);
            }
            return response()->json([
                'response' => [
                    'code' => 200,
                    'api_status' => 1,
                    'filename' => "/logos/$filename",
                ],
            ]);
        } catch (Exception $error) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "Upload failed",
                ],
            ]);
        }
    }

    public function getGroupSetting(Request $request)
    {
        $type = Settings::GROUP_SETTING;
        $groupSetting = Settings::where('type', $type)->get();
        return response()->json([
            'response' => [
                'code' => 200,
                'api_status' => 1,
                'groupSetting' => $groupSetting,
            ],
        ]);
    }

    public function setGroupSetting(Request $request)
    {
        $type = Settings::GROUP_SETTING;
        $groupSetting = $request->groupSetting;
        try {
            foreach ($groupSetting as $key => $value) {
                $exist = Settings::where(['field' => $key, 'type' => $type])->first();
                if ($exist) {
                    $exist->value = $value;
                    $exist->save();
                } else {
                    Settings::create(['field' => $key, 'value' => $value ?? "", 'type' => $type]);
                }
            }
            $groupSetting = Settings::where('type', $type)->get();
            return response()->json([
                'response' => [
                    'code' => 200,
                    'api_status' => 1,
                    'groupSetting' => $groupSetting,
                ],
            ]);
        } catch (Exception $error) {
            return response()->json([
                'response' => [
                    'code' => 400,
                    'api_status' => 0,
                    'message' => "Update failed",
                ],
            ]);
        }
    }
}
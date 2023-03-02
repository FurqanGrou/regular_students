<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TranslateController extends Controller
{
    public function show_translate($lang)
    {
        $language = $lang;

        return view('dashboard.languages.language_view_en', compact('language'));
    }

    public function key_value_store(Request $request)
    {
        $data = openJSONFile($request->id);
        foreach ($request->key as $key => $key) {
            $data[$key] = $request->key[$key];
        }
        saveJSONFile($request->id, $data);
        return back();
    }

}

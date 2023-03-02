<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FavoriteTime;
use Illuminate\Support\Facades\DB;

class FavoriteTimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $favorite_times = FavoriteTime::query()->get();
        return view('dashboard.favorite-times.index', ['favorite_times' => $favorite_times]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("dashboard.favorite-times.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rule = [
            'title_ar' => 'required|string',
            'title_en' => 'required|string',
            'section' => 'required|string',
        ];

        $messages = [
            'title_ar.required' => 'عنوان التوقيت باللغة العربية مطلوب',
            'title_ar.string' => 'يجب التأكد من إدخال عنوان التوقيت باللغة العربية بشكل صحيح',
            'title_en.required' => 'عنوان التوقيت باللغة الانجليزية مطلوب',
            'title_en.string' => 'يجب التأكد من إدخال عنوان التوقيت باللغة الانجليزية بشكل صحيح',
            'section.string' => 'يجب التأكد من إدخال القسم بشكل صحيح',
        ];

        $favorite_time = $this->validate($request, $rule, $messages);

        FavoriteTime::create([
            'title' => ['ar' => $request->title_ar, 'en' => $request->title_en],
            'section' => $request->section,
        ]);

        session()->flash('success', 'تمت الاضافة بنجاح');

        return redirect(route('dashboard.favorite-times.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $favorite_time = FavoriteTime::findOrFail($id);

        return view('dashboard.favorite-times.edit', ['favorite_time' => $favorite_time]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $favorite_time = FavoriteTime::findOrFail($id);

        $rule = [
            'title_ar' => 'required|string',
            'title_en' => 'required|string',
            'section' => 'required|string',
        ];

        $messages = [
            'title_ar.required' => 'عنوان التوقيت باللغة العربية مطلوب',
            'title_ar.string' => 'يجب التأكد من إدخال عنوان التوقيت باللغة العربية بشكل صحيح',
            'title_en.required' => 'عنوان التوقيت باللغة الانجليزية مطلوب',
            'title_en.string' => 'يجب التأكد من إدخال عنوان التوقيت باللغة الانجليزية بشكل صحيح',
            'section.string' => 'يجب التأكد من إدخال القسم بشكل صحيح',
        ];

        $this->validate($request, $rule, $messages);

        $favorite_time->update([
            'title' => ['ar' => $request->title_ar, 'en' => $request->title_en],
            'section' => $request->section,
        ]);

        session()->flash('success', 'تم تحديث البيانات بنجاح');

        return redirect(route('dashboard.favorite-times.edit', $favorite_time->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        FavoriteTime::find($id)->delete();
        session()->flash('success', trans('تم الحذف بنجاح'));
        return redirect(route('dashboard.favorite-times.index'));
    }

}

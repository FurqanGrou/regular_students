<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::query()->get();
        return view('dashboard.courses.index', ['courses' => $courses]);
    }

    public function edit(Course $course)
    {
        return view('dashboard.courses.edit', ['course' => $course]);
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'amount' => 'required|numeric'
        ], [
            'amount.required' => 'يجب عليك إدخال قيمة الرسوم',
            'amount.numeric' => 'يجب عليك إدخال قيمة الرسوم بشكل صحيح'
        ]);

        $course->update([
            'amount' => ($request->amount*100)
        ]);

        return back()->withSuccess('تم تحديث قيمة الرسوم بنجاح');
    }

    public function store(Request $request)
    {
        return back()->withSuccess('تم الإضافة بنجاح');
    }

    public function destroy(Request $request)
    {
        return back()->withSuccess('Done');
    }

}

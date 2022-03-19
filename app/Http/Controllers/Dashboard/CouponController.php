<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\CouponStudent;
use App\Models\Course;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $coupons = Coupon::query()->get();
        return view('dashboard.coupons.index', ['coupons' => $coupons]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $students = Student::query()->get();
        $courses = Course::query()->get();

        return view("dashboard.coupons.create", ['users' => $students, 'courses' => $courses]);
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
            'code' => 'required|string|unique:coupons,code',
            'type' => 'required|string',
            'value' => 'required|numeric',
            'usage_limit' => 'nullable|numeric',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'active' => 'required|numeric',
            'limit_user' => 'sometimes|accepted',
            'users' => 'sometimes|array',
            'course_id' => 'required|exists:courses,id',
        ];

        $messages = [
            'code.required' => 'رمز الكوبون مطلوب',
            'code.unique' => 'رمز الكوبن يجب ان لا يكون مستخدم مسبقاً مطلوب',
            'type.required' => 'يجب التأكد من اختيار توع الكوبون بشكل صحيح',
            'value.required' => 'يجب التأكد من ادخال قيمة الخصم بشكل صحيح',
            'value.numeric' => 'يجب التأكد من ادخال قيمة الخصم بشكل صحيح',
            'usage_limit.numeric' => 'يجب التأكد من ادخال عدد المسموح لهم بشكل صحيح',
            'start_date.date' => 'يجب التأكد من إدخال تاريخ البدء بشكل صحيح',
            'end_date.date' => 'يجب التأكد من إدخال تاريخ الانتهاء بشكل صحيح',
            'active.required' => 'يجب التأكد من إدخال حالة الكوبون بشكل صحيح',
            'active.numeric' => 'يجب التأكد من إدخال حالة الكوبون بشكل صحيح',
            'limit_user.accepted' => 'يجب التأكد من اختيار مرة واحدة أو أكثر لكل طالب بشكل صحيح',
        ];

        $this->validate($request, $rule, $messages);

        if (!$request->specific_users){
            $request['specific_users'] = [];
        }

        $coupon = Coupon::create([
            'code' => $request->code,
            'type' => $request->type,
            'value' => $request->value,
            'usage_limit' => $request->usage_limit,
            'start_date' => $request->start_date ?? Carbon::now('Asia/Riyadh')->toDate(),
            'end_date' => $request->end_date,
            'active' => $request->active,
            'limit_user' => $request->limit_user == 'on' ? 1 : 0,
            'specific_users' => count($request->specific_users) > 0 ? 1 : 0,
            'course_id' => $request->course_id,
        ]);

        if ($request->specific_users){
            foreach($request->specific_users as $user){
                CouponStudent::create([
                    'student_id' => $user,
                    'coupon_id' => $coupon->id,
                ]);
            }
        }

        session()->flash('success', 'تمت الاضافة بنجاح');

        return redirect(route('dashboard.coupons.index'));
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
        $coupon = Coupon::findOrFail($id);
        $students = Student::query()->get();
        $courses = Course::query()->get();

        return view('dashboard.coupons.edit', ['coupon' => $coupon, 'users' => $students, 'courses' => $courses]);
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
        $coupon = Coupon::query()->findOrFail($id);

        $rule = [
            'code' => 'required|string|unique:coupons,code,' . $id,
            'type' => 'required|string',
            'value' => 'required|numeric',
            'usage_limit' => 'nullable|numeric',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'active' => 'required|numeric',
            'limit_user' => 'sometimes|accepted',
            'users' => 'sometimes|array',
            'course_id' => 'required|exists:courses,id',
        ];

        $messages = [
            'code.required' => 'رمز الكوبون مطلوب',
            'code.unique' => 'رمز الكوبن يجب ان لا يكون مستخدم مسبقاً مطلوب',
            'type.required' => 'يجب التأكد من اختيار توع الكوبون بشكل صحيح',
            'value.required' => 'يجب التأكد من ادخال قيمة الخصم بشكل صحيح',
            'value.numeric' => 'يجب التأكد من ادخال قيمة الخصم بشكل صحيح',
            'usage_limit.numeric' => 'يجب التأكد من ادخال عدد المسموح لهم بشكل صحيح',
            'start_date.date' => 'يجب التأكد من إدخال تاريخ البدء بشكل صحيح',
            'end_date.date' => 'يجب التأكد من إدخال تاريخ الانتهاء بشكل صحيح',
            'active.required' => 'يجب التأكد من إدخال حالة الكوبون بشكل صحيح',
            'active.numeric' => 'يجب التأكد من إدخال حالة الكوبون بشكل صحيح',
            'limit_user.accepted' => 'يجب التأكد من اختيار مرة واحدة أو أكثر لكل طالب بشكل صحيح',
        ];

        $this->validate($request, $rule, $messages);

        if (!$request->specific_users){
            $request['specific_users'] = [];
        }

        $coupon_updated = $coupon->update([
            'code' => $request->code,
            'type' => $request->type,
            'value' => $request->value,
            'usage_limit' => $request->usage_limit,
            'start_date' => $request->start_date ?? Carbon::now('Asia/Riyadh')->toDate(),
            'end_date' => $request->end_date,
            'active' => $request->active,
            'limit_user' => $request->limit_user == 'on' ? 1 : 0,
            'specific_users' => count($request->specific_users) > 0 ? 1 : 0,
            'course_id' => $request->course_id,
        ]);

        CouponStudent::where('coupon_id', $coupon->id)->delete();

        if ($request->specific_users){
            foreach($request->specific_users as $user){
                CouponStudent::create([
                    'student_id' => $user,
                    'coupon_id' => $coupon->id,
                ]);
            }
        }

        session()->flash('success', 'تم تحديث البيانات بنجاح');

        return redirect(route('dashboard.coupons.edit', $coupon->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        Coupon::findOrFail($id)->delete();
        session()->flash('success', trans('تم الحذف بنجاح'));
        return redirect(route('dashboard.coupons.index'));
    }

}

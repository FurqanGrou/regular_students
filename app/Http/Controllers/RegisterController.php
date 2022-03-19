<?php

namespace App\Http\Controllers;

use App\FilesHelper;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\Register;
use App\Models\Student;
use App\Models\Subscribe;
use App\Service\Payment\Checkout;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{
    use FilesHelper;

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function register(Request $request)
    {
//        $this->validation($request);
        $data = $request->all();

        $data['name'] = $this->concatenateName($data);

        if ($request->hasFile('student_id')) {
            $data['student_id'] = $this->fileUpload($request->file('student_id'), 'files');
        }

        if ($request->hasFile('parent_id')) {
            $data['parent_id'] = $this->fileUpload($request->file('parent_id'), 'files');
        }
        Register::create($data);
        return view('welcome');
    }

    /**
     * @param array $names
     * @return string
     */
    private function concatenateName(array $names): string
    {
        return $names['first_name'] . ' ' . $names['father_name'] . ' ' . $names['grandfather_name'] . ' ' . $names['nickname'];
    }

    /**
     * @param string $token
     * @param array $customer
     * @return string
     */
    public function payment(string $token, array $customer, $amount)
    {
        $result = (new Checkout())->payment($token, $customer, $amount);

        return $result;
    }

    public function resubscribe(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string',
            'student_number' => 'required|numeric',
            'section'        => 'required|numeric',
            'student_name'   => 'required|string',
            'residence_country' => 'required|exists:countries,id',
            'email' => 'required|email',
        ]);

        if ($request->payment_method == 'hsbc'){
            $request->validate([
                'money_transfer_image_path' => 'required|image',
                'bank_name'     => 'required|string',
                'account_owner' => 'required|string',
                'transfer_date' => 'required|date',
                'bank_reference_number' => 'required|string',
            ]);
        }else{
            $request->validate([
                'token_pay' => 'required|string',
            ]);
        }

        $student = Student::query()
            ->where('serial_number', '=', $request->student_number)
            ->where('section', '=', $request->section)
            ->first();

        if (is_null($student)){
            session()->flash('error', __('resubscribe.The student ID is not in our records'));
            return redirect()->route('semester.registration.index');
        }

        Session::put('student_id', $student->id);
        $course = Course::query()->where('code', 'regular')->first();
        $amount = $course->amount;

        if (isset($request->hidden_apply_coupon) && !empty($request->hidden_apply_coupon)){
            $coupon_code = $request->hidden_apply_coupon;
            $coupon = Coupon::where('code', $coupon_code)->where('course_id', $course->id)->first();

            if (@$coupon->is_valid){
                $discount    = $coupon->getDiscount($course->amount);
                $base_amount = $course->amount;
                $amount = ($base_amount - $discount);
                $coupon->use($student->id);
            }
        }

        if ($request->payment_method == 'checkout_gateway') {

            $customer = ['email' => $request->email, 'name' => $request->student_name];
            $result  = $this->payment($request->token_pay, $customer, $amount);

            $subscribe = Subscribe::query()->create([
                'student_id' => $student->id,
                'country_id' => $request->residence_country,
                'email' => $request->email,
                'payment_method' => 'checkout_gateway',
                'payment_id' => Session::get('payment_id'),
                'reference_number' => Session::get('reference_number'),
                'payment_status' => Session::get('payment_status'),
                'form_type' => 'regular',
                'response_code' => $result->response_code,
                'coupon_id' => $coupon->id ?? null,
                'discount_value' => $discount ?? 0.00,
                'coupon_code' => $coupon->code ?? null,
            ]);

            Session::forget('payment_id');
            Session::forget('payment_status');
            Session::forget('reference_number');

            $redirection = $result->getRedirection();
            if ($redirection){
                return Redirect::to($redirection);
            }else{
                if ($result->approved){
                    session()->flash('success', __('resubscribe.The registration process has been completed successfully'));
                }else{
                    session()->flash('error', __('resubscribe.Payment failed!'));
                }
                return redirect()->route('semester.thankYouPage');
            }

        }else{
            $subscribe = Subscribe::query()->create([
                'student_id' => $student->id,
                'country_id' => $request->residence_country,
                'email' => $request->email,
                'payment_method' => $request->payment_method,
                'money_transfer_image_path' => $request->file('money_transfer_image_path')->store('public/money_transfer_images'),
                'bank_name' => $request->bank_name,
                'account_owner' => $request->account_owner,
                'transfer_date' => $request->transfer_date,
                'bank_reference_number' => $request->bank_reference_number,
                'form_type' => 'regular',
            ]);
        }

        session()->flash('success', __('resubscribe.The registration process has been completed successfully'));
        return redirect()->route('semester.thankYouPage');
    }

    /**
     * @param Request $request
     * @return Application|ResponseFactory|Response
     */
    public function checkStudentExists(Request $request)
    {
        $student = Register::find($request->get('student_id'));

        if ($student) {
            return $student->name;
        }
        return response('not found', 404);
    }

    /**
     * @param Request $request
     */
    private function validation(Request $request)
    {
        $request->validate([
            'sex' => ['sometimes', 'string'],
            'period' => ['sometimes', 'string'],
            'dob' => ['sometimes', 'string'],
            'payment_method' => ['sometimes', 'string'],
            'serial_number' => ['sometimes', 'string'],
            'name' => ['sometimes', 'string'],
            'nationality' => ['sometimes', 'string'],
            'country_of_residence' => ['sometimes', 'string'],
            'city' => ['sometimes', 'string'],
            'address' => ['sometimes', 'string'],
            'post_code' => ['sometimes', 'string'],
            'place_of_birth' => ['sometimes', 'string'],
            'id_passport_number' => ['sometimes', 'string'],
            'student_fathers_mobile_number' => ['sometimes', 'string'],
            'student_mothers_mobile_number' => ['sometimes', 'string'],
            'student_fathers_email' => ['sometimes', 'string'],
            'student_mothers_email' => ['sometimes', 'string'],
            'preferred_language' => ['sometimes', 'string'],
            'student_fathers_name' => ['sometimes', 'string'],
            'student_fathers_employer' => ['sometimes', 'string'],
            'student_mothers_name' => ['sometimes', 'string'],
            'student_mothers_employer' => ['sometimes', 'string'],
            'student_social_status' => ['sometimes', 'string'],
            'student_disease' => ['sometimes', 'string'],
            'participated' => ['sometimes', 'string'],
            'al_nooraniah' => ['sometimes', 'string'],
            'student_id' => ['sometimes', 'string'],
            'parent_id' => ['sometimes', 'string'],
        ]);
    }
}

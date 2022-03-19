<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Course;
use App\Models\FavoriteTime;
use App\Models\Student;
use App\Models\Subscribe;
use App\Service\Payment\Checkout;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use function GuzzleHttp\json_decode;

class SemesterRegistrationController extends Controller
{

    public function index()
    {
        $countries = Country::query()->where('lang', '=', App::getLocale())->get();
        $course = Course::query()->where('code', 'regular')->first();

        return view('old_students', ['countries' => $countries, 'course' => $course]);
    }

    public function thankYouPage()
    {
        if(request()->query('cko-session-id')){
            $client = new Client(['base_uri' => 'https://api.checkout.com']);

            try {
                $response = $client->request('GET', '/payments/' . request()->query('cko-session-id'),
                    [
                        'headers' => [
                            'Authorization' => "sk_8cbe6cf1-3871-4c1c-ae84-cd49b7e2af90"
                        ]
                    ]);

                $data = json_decode($response->getBody()->getContents());

                if ($response->getStatusCode() != 404){
                    Subscribe::query()
                        ->where('payment_id', '=', $data->id)
                        ->update([
                            'payment_status' => $data->status,
                            'response_code'  => $data->actions['response_code'],
                        ]);

                    if ($data->approved){
                        session()->flash('success', __('resubscribe.The registration process has been completed successfully'));
                    }else{
                        session()->flash('error', __('resubscribe.Payment failed'));
                    }

                }else{
                    session()->flash('error', __('resubscribe.Payment failed'));
                }

            }catch (\GuzzleHttp\Exception\ClientException $e) {
//                $response = $e->getResponse();
                session()->flash('error', __('resubscribe.Payment failed'));
            }
        }

        $countries = Country::query()->where('lang', '=', App::getLocale())->get();
        $course = Course::query()->where('code', 'regular')->first();

        if (! (session('error') || session('success')) ) {
            return redirect()->route('semester.registration.index');
        }

        return view('thank-you', ['countries' => $countries, 'course' => $course]);
    }


    public function store(Request $request)
    {

    }

    public function getStudentInfo()
    {
        $student = Student::query()
            ->where('serial_number', '=', \request()->std_number)
            ->where('section', '=', \request()->std_section)
            ->first();


        if ($student){
            return response()->json(['name' => $student->name], 200, [], JSON_UNESCAPED_UNICODE);
        }

        return response()->json(['name' => $student->name], 500);

    }
}

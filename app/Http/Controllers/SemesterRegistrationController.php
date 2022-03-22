<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Course;
<<<<<<< Updated upstream
use App\Models\FavoriteTime;
=======
>>>>>>> Stashed changes
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
<<<<<<< Updated upstream
        $countries = Country::query()->where('lang', '=', App::getLocale())->get();
        $course = Course::query()->where('code', 'regular')->first();

        return view('old_students', ['countries' => $countries, 'course' => $course]);
    }

    public function thankYouPage()
    {
        if(request()->query('cko-session-id')){
            $client = new Client(['base_uri' => 'https://api.checkout.com']);
=======
        if(request()->query('cko-session-id')){
            $client = new Client(['base_uri' => 'https://api.sandbox.checkout.com']);
>>>>>>> Stashed changes

            try {
                $response = $client->request('GET', '/payments/' . request()->query('cko-session-id'),
                    [
                        'headers' => [
<<<<<<< Updated upstream
                            'Authorization' => "sk_8cbe6cf1-3871-4c1c-ae84-cd49b7e2af90"
=======
<<<<<<< Updated upstream
                            'Authorization' => "sk_f9b4d5dd-d1d0-4943-bdbf-e5cd88f37403"
=======
                            'Authorization' => "sk_test_7c21900d-0f6b-4395-af84-9508b39fd5c7"
>>>>>>> Stashed changes
>>>>>>> Stashed changes
                        ]
                    ]);

                $data = json_decode($response->getBody()->getContents());

                if ($response->getStatusCode() != 404){
<<<<<<< Updated upstream
                    Subscribe::query()
                        ->where('payment_id', '=', $data->id)
                        ->update([
                            'payment_status' => $data->status,
                            'response_code'  => $data->actions['response_code'],
                        ]);
=======

                    $subscribe = Subscribe::query()
                        ->where('payment_id', '=', $data->id)
<<<<<<< Updated upstream
                        ->first();

                    $result = $subscribe->update([
                        'payment_status' => $data->status,
                        'response_code'  => $data->actions[0]->response_code ?? '-',
                    ]);
=======
                        ->update([
                            'payment_status' => $data->status,
                            'response_code'  => $data->actions['response_code'] ?? 0,
                        ]);
>>>>>>> Stashed changes
>>>>>>> Stashed changes

                    if ($data->approved){
                        session()->flash('success', __('resubscribe.The registration process has been completed successfully'));
                    }else{
                        session()->flash('error', __('resubscribe.Payment failed'));
                    }

                }else{
                    session()->flash('error', __('resubscribe.Payment failed'));
                }

<<<<<<< Updated upstream
            }catch (\GuzzleHttp\Exception\ClientException $e) {
//                $response = $e->getResponse();
                session()->flash('error', __('resubscribe.Payment failed'));
=======
                return redirect()->route('semester.registration.index');
            }catch (\GuzzleHttp\Exception\ClientException $e) {
//                $response = $e->getResponse();
                session()->flash('error', __('resubscribe.Payment failed'));
                return redirect()->route('semester.registration.index');
>>>>>>> Stashed changes
            }
        }

        $countries = Country::query()->where('lang', '=', App::getLocale())->get();
        $course = Course::query()->where('code', 'regular')->first();

<<<<<<< Updated upstream
        if (! (session('error') || session('success')) ) {
            return redirect()->route('semester.registration.index');
        }

        return view('thank-you', ['countries' => $countries, 'course' => $course]);
    }


=======
        return view('old_students', ['countries' => $countries, 'course' => $course]);
    }

>>>>>>> Stashed changes
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

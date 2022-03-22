<?php

namespace App\Models;

<<<<<<< Updated upstream
use App\Notifications\SubscribeNotification;
=======
>>>>>>> Stashed changes
use App\Services\GoogleSheet;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
<<<<<<< Updated upstream
use Illuminate\Support\Facades\Notification;
=======
>>>>>>> Stashed changes
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class Subscribe extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public static function booted()
    {
<<<<<<< Updated upstream
        static::created(function ($subscribe) {
=======
        static::created(function($subscribe) {
>>>>>>> Stashed changes
            $created_at = Carbon::parse($subscribe->created_at)->timezone('Asia/Riyadh')->format('Y-m-d H:i:s');
            $created_at_formatted = Carbon::parse($subscribe->created_at)->timezone('Asia/Riyadh')->format('Y-m-d');

            $course = Course::query()->where('code', '=', 'regular')->first();
            $price = $course->price - ($subscribe->discount_value / 100);

            $image_path = '-';
<<<<<<< Updated upstream
            if ($subscribe->money_transfer_image_path) {
=======
            if($subscribe->money_transfer_image_path){
>>>>>>> Stashed changes
                $image_path = url(Storage::url($subscribe->money_transfer_image_path));
            }

            $googleSheet = new GoogleSheet();
            $values = [
                [
<<<<<<< Updated upstream
                    $created_at ?? '-', $subscribe->reference_number ?? '-', $created_at_formatted ?? '-',
=======
                    $created_at  ?? '-', $subscribe->reference_number  ?? '-', $created_at_formatted ?? '-',
>>>>>>> Stashed changes
                    'أقرّ باطلاعي نظام التعليم عن بعد الخاص بالمركز.', 'نعم',
                    $subscribe->student->section == 1 ? 'بنين' : 'بنات', $subscribe->student->serial_number ?? '-',
                    $subscribe->student->name ?? '-', $subscribe->country->name, $subscribe->email,
                    $image_path ?? '-', $subscribe->bank_name ?? '-', $subscribe->account_owner ?? '-',
                    $subscribe->transfer_date ?? '-', $subscribe->bank_reference_number ?? '-', $subscribe->payment_method ?? '-',
<<<<<<< Updated upstream
                    $subscribe->payment_id ?? '-', $subscribe->payment_status ?? '-', $subscribe->response_code ?? '-', $subscribe->coupon_code ?? '-', ($subscribe->discount_value / 100) ?? '0.0',
                    $subscribe->student->client_zoho_id ?? '-', $price
=======
<<<<<<< Updated upstream
                    $subscribe->payment_id ?? '-', $subscribe->payment_status ?? '-', $subscribe->response_code ?? '-', $subscribe->coupon_code ?? '-', ($subscribe->discount_value/100) ?? '0.0'
=======
                    $subscribe->payment_id ?? '-', $subscribe->payment_status ?? '-', $subscribe->response_code ?? '-', $subscribe->coupon_code ?? '-',
                    ($subscribe->discount_value / 100) ?? '0.0',
                    $subscribe->student->client_zoho_id ?? '-', $price
>>>>>>> Stashed changes
>>>>>>> Stashed changes
                ],
            ];

            $googleSheet->saveDataToSheet($values);
<<<<<<< Updated upstream

            if ($subscribe->payment_method == 'checkout_gateway' && is_numeric($subscribe->response_code) && in_array($subscribe->payment_status, ['Captured', 'Authorized'])) {
                Notification::route('mail', [$subscribe->email])->notify(new SubscribeNotification($subscribe));
            }

            if ($subscribe->payment_method == 'hsbc') {
                Notification::route('mail', [$subscribe->email])->notify(new SubscribeNotification($subscribe));
            }

        });

        static::updated(function ($subscribe) {
            if ($subscribe->payment_method == 'checkout_gateway') {
=======
        });

        static::updated(function($subscribe) {

            if ($subscribe->payment_method == 'checkout_gateway'){
>>>>>>> Stashed changes
                $created_at = Carbon::parse($subscribe->created_at)->timezone('Asia/Riyadh')->format('Y-m-d H:i:s');
                $created_at_formatted = Carbon::parse($subscribe->created_at)->timezone('Asia/Riyadh')->format('Y-m-d');

                $course = Course::query()->where('code', '=', 'regular')->first();
                $price = $course->price - ($subscribe->discount_value / 100);

                $image_path = '-';
<<<<<<< Updated upstream
                if ($subscribe->money_transfer_image_path) {
=======
                if($subscribe->money_transfer_image_path){
>>>>>>> Stashed changes
                    $image_path = url(Storage::url($subscribe->money_transfer_image_path));
                }

                $googleSheet = new GoogleSheet();
                $values = [
                    [
<<<<<<< Updated upstream
                        $created_at ?? '-', $subscribe->reference_number ?? '-', $created_at_formatted ?? '-',
=======
                        $created_at  ?? '-', $subscribe->reference_number  ?? '-', $created_at_formatted ?? '-',
>>>>>>> Stashed changes
                        'أقرّ باطلاعي نظام التعليم عن بعد الخاص بالمركز.', 'نعم',
                        $subscribe->student->section == 1 ? 'بنين' : 'بنات', $subscribe->student->serial_number ?? '-',
                        $subscribe->student->name ?? '-', $subscribe->country->name, $subscribe->email,
                        $image_path, $subscribe->bank_name ?? '-', $subscribe->account_owner ?? '-',
                        $subscribe->transfer_date ?? '-', $subscribe->bank_reference_number ?? '-', $subscribe->payment_method ?? '-',
<<<<<<< Updated upstream
                        $subscribe->payment_id ?? '-', $subscribe->payment_status ?? '-', $subscribe->response_code ?? '-', $subscribe->coupon_code ?? '-', ($subscribe->discount_value / 100) ?? '0.0',
                        $subscribe->student->client_zoho_id ?? '-', $price
=======
<<<<<<< Updated upstream
                        $subscribe->payment_id ?? '-', $subscribe->payment_status ?? '-', $subscribe->response_code ?? '-', $subscribe->coupon_code ?? '-', ($subscribe->discount_value/100) ?? '0.0'
=======
                        $subscribe->payment_id ?? '-', $subscribe->payment_status ?? '-', $subscribe->response_code ?? '-', $subscribe->coupon_code ?? '-', ($subscribe->discount_value / 100) ?? '0.0',
                        $subscribe->student->client_zoho_id ?? '-', $price
>>>>>>> Stashed changes
>>>>>>> Stashed changes
                    ],
                ];

                $googleSheet->saveDataToSheet($values);
<<<<<<< Updated upstream

                if (is_numeric($subscribe->response_code) && in_array($subscribe->payment_status, ['Captured', 'Authorized'])) {
                    Notification::route('mail', [$subscribe->email])->notify(new SubscribeNotification($subscribe));
                }
=======
>>>>>>> Stashed changes
            }
        });

    }
}

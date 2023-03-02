<?php

namespace App\Models;

use App\Notifications\SubscribeNotification;
use App\Services\GoogleSheet;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;
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

    public function customPrice()
    {
        return $this->belongsTo(CustomPrice::class, 'custom_price_id');
    }

    public static function booted()
    {
        static::created(function ($subscribe) {
            $created_at = Carbon::parse($subscribe->created_at)->timezone('Asia/Riyadh')->format('Y-m-d H:i:s');
            $created_at_formatted = Carbon::parse($subscribe->created_at)->timezone('Asia/Riyadh')->format('Y-m-d');

            $course = Course::query()->where('code', '=', 'regular')->first();
            $price = $course->price - ($subscribe->discount_value / 100);
            $net_price = $course->price - ($subscribe->discount_value / 100) - 15;

            $image_path = '-';

            $discount_reason_image = '-';
            if ($subscribe->discount_reason_image) {
                $discount_reason_image = url(Storage::url($subscribe->discount_reason_image));
            }

            $googleSheet = new GoogleSheet();
            $values = [
                [
                    $created_at ?? '-', $subscribe->reference_number ?? '-', $created_at_formatted ?? '-',
                    'أقرّ باطلاعي نظام التعليم عن بعد الخاص بالمركز.', 'نعم',
                    $subscribe->student->section == 1 ? 'بنين' : 'بنات', $subscribe->student->serial_number ?? '-',
                    $subscribe->student->name ?? '-', $subscribe->country->name, $subscribe->email,
                    $image_path ?? '-', $subscribe->bank_name ?? '-', $subscribe->account_owner ?? '-',
                    $subscribe->transfer_date ?? '-', $subscribe->bank_reference_number ?? '-', $subscribe->payment_method ?? '-',
                    $subscribe->payment_id ?? '-', $subscribe->payment_status ?? '-', $subscribe->response_code ?? '-', $subscribe->coupon_code ?? '-', ($subscribe->discount_value / 100) ?? '0.0',
                    $subscribe->student->client_zoho_id ?? '-', $price, $net_price, $subscribe->favorite_time ?? '-',

                    $subscribe->customPrice->discount_value ?? '-', $subscribe->customPrice->discount_percent ?? '-',
                    $subscribe->customPrice->discount_reason ?? '-', $discount_reason_image ?? '-',
                ],
            ];

            $googleSheet->saveDataToSheet($values);

            if ($subscribe->payment_method == 'checkout_gateway' && is_numeric($subscribe->response_code) && in_array($subscribe->payment_status, ['Captured', 'Authorized'])) {
                Notification::route('mail', [$subscribe->email])->notify(new SubscribeNotification($subscribe));
            }

        });

        static::updated(function ($subscribe) {
            if ($subscribe->payment_method == 'checkout_gateway') {
                $created_at = Carbon::parse($subscribe->created_at)->timezone('Asia/Riyadh')->format('Y-m-d H:i:s');
                $created_at_formatted = Carbon::parse($subscribe->created_at)->timezone('Asia/Riyadh')->format('Y-m-d');

                $course = Course::query()->where('code', '=', 'regular')->first();
                $price = $course->price - ($subscribe->discount_value / 100);
                $net_price = $course->price - ($subscribe->discount_value / 100) - 15;

                $image_path = '-';

                $discount_reason_image = '-';
                if ($subscribe->discount_reason_image) {
                    $discount_reason_image = url(Storage::url($subscribe->discount_reason_image));
                }

                $googleSheet = new GoogleSheet();
                $values = [
                    [
                        $created_at ?? '-', $subscribe->reference_number ?? '-', $created_at_formatted ?? '-',
                        'أقرّ باطلاعي نظام التعليم عن بعد الخاص بالمركز.', 'نعم',
                        $subscribe->student->section == 1 ? 'بنين' : 'بنات', $subscribe->student->serial_number ?? '-',
                        $subscribe->student->name ?? '-', $subscribe->country->name, $subscribe->email,
                        $image_path, $subscribe->bank_name ?? '-', $subscribe->account_owner ?? '-',
                        $subscribe->transfer_date ?? '-', $subscribe->bank_reference_number ?? '-', $subscribe->payment_method ?? '-',
                        $subscribe->payment_id ?? '-', $subscribe->payment_status ?? '-', $subscribe->response_code ?? '-', $subscribe->coupon_code ?? '-', ($subscribe->discount_value / 100) ?? '0.0',
                        $subscribe->student->client_zoho_id ?? '-', $price, $net_price, $subscribe->favorite_time ?? '-',

                        $subscribe->customPrice->discount_value ?? '-', $subscribe->customPrice->discount_percent ?? '-',
                        $subscribe->customPrice->discount_reason ?? '-', $discount_reason_image ?? '-',
                    ],
                ];

                $googleSheet->saveDataToSheet($values);

                if (is_numeric($subscribe->response_code) && in_array($subscribe->payment_status, ['Captured', 'Authorized'])) {
                    Notification::route('mail', [$subscribe->email])->notify(new SubscribeNotification($subscribe));
                }
            }
        });

    }
}

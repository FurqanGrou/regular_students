<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>report</title>
</head>
<style>
    td {
        text-align: center;
        font-size: 20px;
        font-weight: bold;
        border: gray 1px solid;
        width: 400px;
        height: 35px;
    }

    table {
        border: gray 1px solid;
    }
</style>

<body>

@php
    $relation = 'student';
    if ($details->form_type == 'stopped-students'){
        $relation = 'student';
    }

    if ($details->form_type == 'new-students'){
        $relation = 'newStudent';
        $details->newStudent['name'] = $details->{$relation}->first_name . ' ' . $details->{$relation}->father_name . ' ' . $details->{$relation}->grandfather_name . ' ' . $details->{$relation}->family_name;
        $details->newStudent['serial_number'] = '-';
    }
@endphp
<div style="border: 2px solid black; width: 70%; margin: 0 auto;">
    <div style="text-align: center;font-family: arial, sans-serif">
        <img src="{{ public_path('dashboard\assets\img\logo3.png') }}" alt="">
        <h1> {{ __('Second semester 2022') }}</h1>
    </div>

    <div>

        {{-- Detaiels--}}
        <table dir="rtl" style="width: 100%; font-weight: bold; border-collapse: collapse;">
            <tbody>
            <tr style="height: 40px;">
                <td style="width: 30%;padding-right:10px;text-align: right;border: 1px solid gray; font-size: 16px;font-family: arial, sans-serif">
                    {{ __('name') }}
                </td>
                <td style="border: 1px solid gray;width: 30%; font-size: 16px; text-align: center;font-family: arial, sans-serif">
                    {{ __('Serial Number') }}
                </td>
                <td style="border: 1px solid gray;width: 40%; font-size: 16px; text-align: center;font-family: arial, sans-serif">
                    {{ __('Section') }}
                </td>
            </tr>
            <tr style="height: 40px">
                <td style="width: 30%;padding-right:10px;text-align: right;border: 1px solid gray; font-size: 16px;font-family: arial, sans-serif">
                    {{ $details->{$relation}->name }}
                </td>
                <td style="border: 1px solid gray;width: 30%; font-size: 16px; text-align: center;font-family: arial, sans-serif">
                    {{ $details->{$relation}->serial_number }}
                </td>
                <td style="border: 1px solid gray;width: 40%; font-size: 16px; text-align: center;font-family: arial, sans-serif">
                    {{ $details->{$relation}->section == '1' ? __('Males') : __('Females') }}
                </td>

            </tr>
            <tr style="height: 40px">
                <td style="width: 30%;padding-right:10px;text-align: right;border: 1px solid gray; font-size: 16px;font-family: arial, sans-serif">
                    {{ __('Payment Method') }}
                </td>
                <td style="border: 1px solid gray;width: 30%; font-size: 16px; text-align: center;font-family: arial, sans-serif">
                    {{ __('Credit Card') }}
                </td>
                <td style="border: 1px solid gray;width: 80px; font-size: 16px; text-align: center;font-family: arial, sans-serif">
                    ----
                </td>
            </tr>

            </tbody>
        </table>

    </div>
</div>
</body>

</html>

<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Imports\CustomPricesImport;
use App\Imports\StudentImport;
use App\Models\CustomPrice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportExportController extends Controller
{

    public function __construct(){
        ini_set('max_execution_time', 600);
        ini_set('memory_limit', '60m');
    }

    public function importStudents(Request $request)
    {
        $request->validate([
            'section' => 'required|in:1,2'
        ]);

        if (is_null($request->file_path)){
            return back()->withError('يجب عليك إرفاق الملف المطلوب');
        }

        Excel::import(new StudentImport($request->section), $request->file_path);

        return back()->withSuccess('تم تحديث بيانات الطلاب بنجاح');
    }

    public function showImportStudents()
    {
        return view('dashboard.import-export.students');
    }

    public function exportSubscribes()
    {
        return view('dashboard.import-export.subscribes');
    }

    public function importCustomPrices()
    {
        return view('dashboard.import-export.custom-prices');
    }

    public function importCustomPricesStore(Request $request)
    {
        if (is_null($request->file_path)){
            return back()->withError('يجب عليك إرفاق الملف المطلوب');
        }

        Excel::import(new CustomPricesImport(), $request->file_path);

        return back()->withSuccess('تم تحديث الأسعار المخصصة بنجاح');
    }

    public function exportOldSubscribes()
    {
        $customers = \App\Models\Subscribe::query()->where('form_type', '=', 'stopped-students')->get();

        $content = [];
        foreach ($customers as  $customer) {
            $created_at_formatted = Carbon::parse($customer->created_at)->timezone('Asia/Riyadh')->format('Y-m-d');

            $content [] = $customer->reference_number ?? '-';
            $content [] = $created_at_formatted;
            $content [] = $customer->stoppedStudent->student->section ?? '-';
            $content [] = $customer->stoppedStudent->student->serial_number ?? '-';
            $content [] = $customer->stoppedStudent->student->name ?? '-';
            $content [] = $customer->stoppedStudent->favorite_time ?? '-';
            $content [] = $customer->stoppedStudent->bod ?? '-';
            $content [] = '-';
            $content [] = '-';
            $content [] = '-';
            $content [] = '-';
            $content [] = $customer->stoppedStudent->country->code ?? '-';
            $content [] = $customer->stoppedStudent->residenceCountry->code ?? '-';
            $content [] = $customer->stoppedStudent->city ?? '-';
            $content [] = $customer->stoppedStudent->address ?? '-';
            $content [] = $customer->stoppedStudent->postal_code ?? '-';
            $content [] = $customer->stoppedStudent->place_birth ?? '-';
            $content [] = $customer->stoppedStudent->id_number ?? '-';
            $content [] = $customer->stoppedStudent->father_whatsApp_number ?? '-';
            $content [] = $customer->stoppedStudent->mother_whatsApp_number ?? '-';
            $content [] = $customer->stoppedStudent->father_email ?? '-';
            $content [] = $customer->stoppedStudent->mother_email ?? '-';
            $content [] = $customer->stoppedStudent->preferred_language ?? '-';
            $content [] =  '-';
            $content [] =  '-';
            $content [] =  '-';
            $content [] =  '-';
            $content [] =  '-';
            $content [] =  '-';
            $content [] =  '-';
            $content [] =  '-';
            $content [] =  '-';
            $content [] =  '-';
        }

        // file name to download
        $fileName = "new_students.txt";

        // make a response, with the content, a 200 response code and the headers
        return \Illuminate\Support\Facades\Response::json($content, 200, [
            'Content-type' => 'text/plain',
            'Content-Disposition' => sprintf('attachment; filename="%s"', $fileName),
        ], JSON_UNESCAPED_UNICODE);

    }

    public function resetCustomPrices()
    {
        return CustomPrice::where('status', '=', '1')->delete();
    }

}

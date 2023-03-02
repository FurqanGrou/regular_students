<?php

namespace App\Http\Controllers;

use App\Imports\CountryImport;
use App\Imports\CouponImport;
use App\Imports\StudentImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function __construct(){
        ini_set('max_execution_time', 600);
        ini_set('memory_limit', '120M');
    }

    public function importStudents()
    {
        Excel::import(new StudentImport(), 'students_males_2.xlsx');
        dd('Import Done');
    }

    public function importCountries()
    {
        Excel::import(new CountryImport(), 'countries.xlsx');

        dd('Import Done');
    }

    public function importCoupons()
    {
        Excel::import(new CouponImport(), 'coupons.xlsx');

        dd('Import Coupons Done');
    }


}

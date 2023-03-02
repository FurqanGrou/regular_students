<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $data)
 */
class Register extends Model
{
    use HasFactory;

    protected $fillable = [
        'sex',
        'period',
        'dob',
        'payment_method',
        'serial_number',
        'name',
        'nationality',
        'country_of_residence',
        'city',
        'address',
        'post_code',
        'place_of_birth',
        'id_passport_number',
        'student_fathers_mobile_number',
        'student_mothers_mobile_number',
        'student_fathers_email',
        'student_mothers_email',
        'preferred_language',
        'student_fathers_name',
        'student_fathers_employer',
        'student_mothers_name',
        'student_mothers_employer',
        'student_social_status',
        'student_disease',
        'participated',
        'al_nooraniah',
        'student_id',
        'parent_id',
    ];
}

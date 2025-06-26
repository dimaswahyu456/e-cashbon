<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cashbon extends Model
{
    use HasFactory;
    protected $table = 'CASHBON';
    protected $fillable = [
        'CO_ID',
        'VEND_ID',
        'VEND_NAME',
        'NPWP',
        'CONTACT',
        'PHONE',
        'ADDRESS',
        'CITY',
        'REMARKS',
        'STATUS',
        'COA_ID',
        'COA_ADVANCE',
        'COA_GRN',
        'VEND_TYPE',
        'COA_CASHBON',
        'TIMEEDIT',
        'TIMEINPUT',
        'USEREDIT',
        'USERINPUT'
    ];
}

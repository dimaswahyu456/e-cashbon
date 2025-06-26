<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;
    protected $table = 'WAREHOUSE';
    protected $fillable = [
        'CO_ID',
        'WAREHOUSE_ID',
        'WAREHOUSE_NAME',
        'ADDRESS',
        'CITY',
        'REMARKS',
        'STATUS',
        'TIMEEDIT',
        'TIMEINPUT',
        'USEREDIT',
        'USERINPUT'
    ];
}

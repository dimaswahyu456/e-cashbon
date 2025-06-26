<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashbonDetail extends Model
{
    use HasFactory;
    protected $table = 'cashbon_detail';
    protected $fillable = [
        'id_cashbon',
        'doc_date',
        'keterangan',
        'doc_type',
        'total',
        'image',
        'user_input',
        'user_edit',
        'created_at',
        'updated_at'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approved extends Model
{
    use HasFactory;
    protected $table = 'approved';
    protected $fillable = [
        'doc_id',
        'name_approved',
        'role_id',
        'notes',
        'status',
        'approved_date',
        'created_at',
        'updated_at'
    ];
}

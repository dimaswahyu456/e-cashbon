<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleType extends Model
{
    use HasFactory;
    protected $table = 'SALE_TYPE';
    protected $fillable = [
        'CO_ID',
        'ST_ID',
        'ST_NAME',
        'COA_SALE',
        'COA_DISCOUNT',
        'COA_HPP',
        'REMARKS',
        'STATUS',
        'TIMEEDIT',
        'TIMEINPUT',
        'USEREDIT',
        'USERINPUT'
    ];

    public function Account()
    {
        return $this->hasOne(Account::class, ['co_id', 'coa_id'], ['co_id', 'coa_id']);
    }
}

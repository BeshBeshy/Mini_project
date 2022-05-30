<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'Amount',
        'Paid_on',
        'Details',
        'transaction_id',
    ];

    public function transaction(){
        return $this->belongsTo('App\Models\Transaction', 'transaction_id');
    }
}

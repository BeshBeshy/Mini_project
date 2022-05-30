<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'Amount',
        'PaidAmount',
        'Duo_on',
        'Vat',
        'Is_vat_included',
        'category_id',
        'sub_category_id',
        'payer_id',
        'status_id',
    ];

    protected $guarded = [

    ];

    public function payer(){
        return $this->belongsTo('App\Models\User', 'payer_id', 'id');
    }

    public function category(){
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function subCategory(){
        return $this->belongsTo('App\Models\SubCategory', 'sub_category_id');
    }

    public function status(){
        return $this->belongsTo('App\Models\Status', 'status_id');
    }

    public function getIsVatIncludedAttribute($value)
    {
        if ($value)
        {
            return true;
        }
        else{
            return false;
        }
    }
}

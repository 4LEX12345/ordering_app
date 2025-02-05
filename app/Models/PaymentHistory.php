<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    use HasFactory;
    
    public function paymentMethod(){
        return $this->belongsTo(PaymentMethod::class, 'payment_method');
    }
}

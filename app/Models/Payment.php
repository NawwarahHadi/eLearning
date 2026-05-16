<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model {
    protected $fillable = ['student_id', 'billing_month', 'total_amount', 'status', 'payment_method'];

    public function student() {
        return $this->belongsTo(User::class, 'student_id');
    }
}

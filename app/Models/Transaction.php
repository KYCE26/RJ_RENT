<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
    'user_id', 'car_id', 'date_start', 'date_end', 'total', 'status', 'payment_proof',
];
    use HasFactory;
    protected $guarded = [];
public function user(){
    return $this->belongsTo(User::class);
}
public function car(){
    return $this->belongsTo(Car::class);
}

}

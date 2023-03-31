<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['member_id', 'date_start' , 'date_end' , 'status'];
    // protected $casts = [
    //     'book_id' => 'array'
    // ];

    public function members() {
        return $this->belongsTo('App\Models\Member', 'member_id');
    }

    public function books() {
        return $this->hasMany('App\Models\Book', 'book_id');
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id');
    }

}

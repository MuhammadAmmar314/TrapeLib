<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parents extends Model
{
    use HasFactory;

    protected $table = 'parents';

    protected $fillable = [
        'member_id'
    ];

    protected $primaryKey = 'id';

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function childs()
    {
        return $this->hasMany(Child::class);
    }
}

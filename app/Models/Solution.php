<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Solution extends Model
{
    public function problem()
    {
        return $this->belongsTo(Problem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

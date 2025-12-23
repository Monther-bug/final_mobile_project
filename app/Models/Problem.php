<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Problem extends Model
{
    /** @use HasFactory<\Database\Factories\ProblemFactory> */
    use HasFactory;

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }

    public function solutions()
    {
        return $this->hasMany(Solution::class);
    }

    public function testCases()
    {
        return $this->hasMany(TestCase::class);
    }
}

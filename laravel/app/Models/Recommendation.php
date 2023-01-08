<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'parent_id',
        'child_id',
    ];
}

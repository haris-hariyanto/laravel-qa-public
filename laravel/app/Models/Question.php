<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $with = ['user', 'subject', 'grade'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}

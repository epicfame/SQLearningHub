<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = 'questions';

    protected $guarded = [
        'id',
    ];

    public $timestamps = false;

    public function answer()
    {
        return $this->hasOne(Answer::class, 'question_id');
    }
}

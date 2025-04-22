<?php

namespace App\models\website;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $table = 'faq';
    protected $fillable = ['question', 'answer', 'status'];
}

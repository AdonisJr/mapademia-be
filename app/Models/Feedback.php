<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{

    use HasFactory;

    protected $fillable = ['user_id', 'business_id', 'comment', 'stars'];

    // Feedback belongs to a Business
    public function business()
    {
        return $this->belongsTo(Business::class); 
    }

    // Feedback belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class); 
    }
}

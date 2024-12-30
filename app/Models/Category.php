<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
    ];

    // You can add a relationship to the Business model here
    public function businesses()
    {
        return $this->hasMany(Business::class);
    }
}

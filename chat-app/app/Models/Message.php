<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;
    protected $fillable = ['message']; 

    // Define the relationship to the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
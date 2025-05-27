<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'b_title', 'b_author', 'b_category', 'b_availability'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'book_id');
    }
        
}

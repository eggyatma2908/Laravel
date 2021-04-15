<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    // wajib mendaftarkan field apa saja yang kita gunakan pada tabel tersebut jika tidak didaftarkan maka tidak dapat insert data ke database
    protected $fillable = [
        'title', 'slug', 'description', 'price', 'rating'
        ];
    
    // kita perlu merelasikan setiap tabel
    public function imageUploaded() {
        return $this->hasMany(ImageUploaded::class, 'product_id', 'id');
    }
}

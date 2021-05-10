<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function __construct(Product $product) {
        $this->product = $product;
    }

    public function index() {
        $products = $this->product->get();
        
        return view('pages.products', [
            'products' => $products
        ]);
    }

    public function detail($id) {
        $product = $this->product->find($id);

        // find($id) jika id tidak ditemukan maka akan tampil null atau data yang dikembalikan kosong
        // findOrFail($id) jika id tidak ditemukan maka laravel akan mengarahkan ke 404 not found

        return view('pages.products_details', [
            'product' => $product
        ]);
    }
}

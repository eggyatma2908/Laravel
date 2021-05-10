<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class LandingPageController extends Controller
{
    public function __construct(Product $product) {
        $this->product = $product;
    }

    public function index() {
        $latestProduct = $this->product->orderBy('rating', 'DESC')->limit(8)->get();

        // ->get() untuk menampilkan lebih dari satu data
        // ->first() untuk menampilkan single data
        
        return view('pages.landing_page', [
            'headerColor'    => true,
            'landing'        => true,
            'latestProduct' => $latestProduct
        ]);
    }
}

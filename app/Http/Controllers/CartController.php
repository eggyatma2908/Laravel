<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Http;
use App\Models\Order;

class CartController extends Controller
{
    public function __construct(Order $order) {
        $this->order = $order;
    }

    public function index() {
        $cart = $this->order->cart(Auth::id());
        return view('pages.cart', [
            'cart' => $cart
        ]);
    }

    public function cart(Request $request) {
        $data = $request->except('_token');
        $data['user_id'] = Auth::user()->id;

        // Check cart
        $cart = $this->order->cart(Auth::id());
        // Set uuid value

        if (count($cart) > 0 ) {
            $data['uuid'] = $cart[0]->uuid;
        } else {
            $data['uuid'] = Uuid::uuid4();
        }

        $post = Http::withToken(session()->get('user_token'))->withHeaders([
            'Accept' => 'application/json'
        ])->post(env('APP_URL').'api/cart/add', $data);
        
        if (json_decode($post->getStatusCode()) == 200) {
            return response()->json($post->body(), $post->getStatusCode());
        }

        return response()->json($post->json(), $post->getStatusCode());
    }

    public function removeFromCart($id) {
        $post = Http::withToken(session()->get('user_token'))->withHeaders([
            'Accept' => 'application/json'
        ])->delete(env('APP_URL').'api/cart/remove/'.$id);
        
        if (json_decode($post->getStatusCode()) == 200) {
            return response()->json($post->body(), $post->getStatusCode());
        }

        return response()->json($post->json(), $post->getStatusCode());
    }

    public function checkout(Request $request) {
        $post = Http::withToken(session()->get('user_token'))->withHeaders([
            'Accept' => 'application/json'
        ])->post(env('APP_URL').'api/cart/checkout', $request->except('_token'));
        
        if (json_decode($post->getStatusCode()) == 200) {
            return response()->json($post->body(), $post->getStatusCode());
        }

        return response()->json($post->json(), $post->getStatusCode());
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orders = [
            [
                "id" => 1,
                "product" => "Tikka Masala",
                "status" => "delivered",
                "quantity" => 2,
                "price" => 10,
                "delivary_driver" => "Hektor Akhani",
                "shop_name" => "Inkum",
                "user_id" => $user->id
            ],
            [
                "id" => 2,
                "product" => "Burger",
                "status" => "on route",
                "quantity" => 1,
                "price" => 20,
                "delivary_driver" => "Indo Kumos",
                "shop_name" => "Hissidan",
                "user_id" => $user->id
            ],
            [
                "id" => 1,
                "product" => "Hali,",
                "status" => "delivered",
                "quantity" => 2,
                "price" => 5,
                "delivary_driver" => "Alex Jhon",
                "shop_name" => "Letlor",
                "user_id" => $user->id
            ],
        ];

        return view('dashboard', compact('orders'));
    }
}
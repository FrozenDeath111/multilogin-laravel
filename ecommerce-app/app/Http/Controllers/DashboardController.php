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
                "product" => "Hebron",
                "status" => "completed",
                "quantity" => 4,
                "price" => 30,
                "user_id" => $user->id
            ],
            [
                "id" => 2,
                "product" => "Telldorn",
                "status" => "pending",
                "quantity" => 2,
                "price" => 45,
                "user_id" => $user->id
            ],
            [
                "id" => 3,
                "product" => "Helderon",
                "status" => "pending",
                "quantity" => 3,
                "price" => 20,
                "user_id" => $user->id
            ],
        ];

        return view('dashboard', compact('orders'));
    }
}
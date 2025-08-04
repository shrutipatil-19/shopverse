<?php

namespace App\Http\Controllers;

use App\Models\Product;

abstract class Controller
{
    public function index()
    {

        return response()->json(
            Product::with(['category', 'brand', 'user'])
                ->where('status', 'active')
                ->where('visibility', true)
                ->get()
        );
    }
}

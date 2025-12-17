<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\StoreSetting;

class CartController extends Controller
{
    public function index()
    {
        $waNumber = StoreSetting::where('key', 'wa_number')->value('value') ?? '6281234567890'; // Default fallback
        $isStoreOpen = StoreSetting::where('key', 'is_store_open')->value('value') === 'true'; // Default false if not set, or check string 'true'

        return Inertia::render('Cart/Index', [
            'waNumber' => $waNumber,
            'isStoreOpen' => $isStoreOpen,
        ]);
    }
}

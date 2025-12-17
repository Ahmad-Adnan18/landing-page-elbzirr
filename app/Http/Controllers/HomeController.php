<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;

class HomeController extends Controller
{
    public function index()
    {
        $signatureProducts = Product::with(['category', 'variants'])
            ->where('is_active', true)
            ->where('is_signature', true)
            ->latest()
            ->get();

        $testimonials = \App\Models\Testimonial::where('is_active', true)
            ->latest()
            ->take(6)
            ->get();

        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'signatureProducts' => $signatureProducts,
            'testimonials' => $testimonials,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Sponsorship;

/** Halaman landing publik: menampilkan peluang sponsorship yang sedang dibuka. */
class LandingController extends Controller
{
    public function index()
    {
        $sponsorships = Sponsorship::open()
            ->with('sponsor')
            ->latest()
            ->take(4)
            ->get();

        return view('welcome', compact('sponsorships'));
    }
}

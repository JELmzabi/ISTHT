<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class RapportsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function index(Request $request)
    {
        return Inertia::render('Rapports/Index');
    }
}

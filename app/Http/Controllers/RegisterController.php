<?php

namespace App\Http\Controllers;

use App\Models\Pool;

class RegisterController extends Controller
{
    // GET /register — tampilkan form pengajuan admin
    public function showRegistrationForm()
    {
        $pools = Pool::orderBy('name')->get();
        return view('auth.register', compact('pools'));
    }
}

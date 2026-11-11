<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\PanitiaProfile;

abstract class PanitiaController extends Controller
{
    protected function profile(): PanitiaProfile
    {
        $user = auth()->user();

        return $user->panitiaProfile ?? PanitiaProfile::create([
            'user_id'         => $user->id,
            'nama_organisasi' => $user->name,
        ]);
    }
}
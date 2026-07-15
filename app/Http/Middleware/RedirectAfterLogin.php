<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectAfterLogin
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->hasRole('Wali Santri')) {
            return redirect()->route('wali.dashboard');
        }

        return redirect()->route('admin.dashboard');
    }
}

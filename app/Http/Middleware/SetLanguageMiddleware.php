<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\App;

class SetLanguageMiddleware
{
    public function handle($request, Closure $next)
    {
        if (in_array($request->header('Accept-language'), languages())) {
            $locale = $request->header('Accept-language');
            if (authCheck()) {
                $user = authUser();
                if ($locale !== $user->locale) {
                    $user->update(['locale' => $locale]);
                }
            }
            App::setLocale($locale);
            Carbon::setLocale($locale);
        }

        return $next($request);
    }
}

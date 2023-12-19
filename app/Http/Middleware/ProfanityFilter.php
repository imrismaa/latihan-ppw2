<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ProfanityFilter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $content = $request->input('review');

        if (isProfanity($content)) {
            return redirect()->back()->with('error', 'Review mengandung kata-kata tidak sopan dan perlu dimoderasi.');
        }

        return $next($request);
    }

    private function isProfanity($text)
    {
        $profanityList = ['jelek', 'shibal', 'bjir'];
        $lowercaseText = strtolower($text);

        foreach ($profanityList as $profanity) {
            if (strpos($lowercaseText, $profanity) !== false) {
                return true;
            }
        }

        return false;
    }
}

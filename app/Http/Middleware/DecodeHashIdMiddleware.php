<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Vinkla\Hashids\Facades\Hashids;

class DecodeHashIdMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $fieldName): Response
    {
        if ($request->has($fieldName)) {
            $request->replace([
                $fieldName => $this->decodeInput($request, $fieldName),
            ] + $request->all());
        }

        return $next($request);
    }

    /**
     * @return array|int|int[]|mixed
     */
    public function decodeInput(Request $request, $fieldName): mixed
    {
        $decodeInput = Hashids::connection('user-id')->decode($request->get($fieldName));
        if (is_array($decodeInput) && count($decodeInput)) {
            $decodeInput = $decodeInput[0];
        }

        return $decodeInput;
    }
}

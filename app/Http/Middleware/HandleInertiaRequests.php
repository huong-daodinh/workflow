<?php

namespace App\Http\Middleware;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                'can' => [
                  'department' => $request->user() && $request->user()->role === 'admin',
                  'user' => $request->user() && $request->user()->role !== 'member',
                ]
            ],
            'flash'=> fn () => $request->session()->get('flash'),
            'flash_data'=> fn () => $request->session()->get('flash_data'),
            'current_route_name' => fn () => \Illuminate\Support\Facades\Route::currentRouteName(),
            'current_locale' => fn () => app()->getLocale(),
        ];
    }
}

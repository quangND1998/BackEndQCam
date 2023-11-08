<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

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
    public function version(Request $request): ?string
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
        //  return array_merge(parent::share($request), [
        //     'auth' => [
        //         'user' => $request->user() ,
        //         // 'roles'=>  Auth::user()->roles,

        //     ],
        //     'flash' => function () use ($request) {
        //         return [
        //             'success' => $request->session()->get('success'),
        //             'warning' => $request->session()->get('warning')

        //         ];
        //     },
        //       'ziggy' => fn () => [
        //         ...(new Ziggy)->toArray(),
        //         'location' => $request->url(),
        //     ],
        //     'author'=> 'Quang Nguyen'
        // ]);
        return [
            ...parent::share($request),
            'auth.can' => $request->user() ? $request->user()->getPermissionArray() : [],
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'flash' => function () use ($request) {
                return [
                    'success' => $request->session()->get('success'),
                    'warning' => $request->session()->get('warning')

                ];
            },
            'author' => 'Quang Nguyens'
        ];
    }
}

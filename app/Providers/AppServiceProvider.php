<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Knuckles\Scribe\Scribe;
use Symfony\Component\HttpFoundation\Request;
use Knuckles\Camel\Extraction\ExtractedEndpointData;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (class_exists(\Knuckles\Scribe\Scribe::class)) {
            Scribe::beforeResponseCall(function (Request $request, ExtractedEndpointData $endpointData) {
                $token = User::first()->createToken('GeOsm')->accessToken;
                $request->headers->add(["Authorization" => "Bearer $token"]);
            });
        }
    }
}

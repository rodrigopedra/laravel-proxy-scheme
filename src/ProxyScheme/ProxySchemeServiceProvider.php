<?php

namespace RodrigoPedra\ProxyScheme;

use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;

class ProxySchemeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->afterResolving(UrlGenerator::class, function (UrlGenerator $url, Container $app) {
            $request = $app->make(Request::class);
            $scheme = $request->header('X-Forwarded-Proto');

            if (\in_array($scheme, ['http', 'https'], true)) {
                $url->forceScheme($scheme);
            }
        });
    }
}

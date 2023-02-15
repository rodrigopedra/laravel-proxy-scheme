<?php

namespace RodrigoPedra\ProxyScheme;

use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;
use Laravel\Octane\Events\RequestReceived as OctaneRequestReceived;

class ProxySchemeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if (isset($_SERVER['LARAVEL_OCTANE'])) {
            return;
        }

        $this->app->afterResolving(UrlGenerator::class, function (UrlGenerator $url, Container $app) {
            $request = $app->make(Request::class);

            $this->proxyScheme($request, $url);
        });
    }

    public function boot(Dispatcher $events): void
    {
        if (! isset($_SERVER['LARAVEL_OCTANE'])) {
            return;
        }

        $events->listen(OctaneRequestReceived::class, function (OctaneRequestReceived $event) {
            $this->proxyScheme($event->request, $event->app->make(UrlGenerator::class));
        });
    }

    public function proxyScheme(Request $request, UrlGenerator $url)
    {
        $scheme = $request->header('X-Forwarded-Proto');

        if (\in_array($scheme, ['http', 'https'], true)) {
            $url->forceScheme($scheme);
        }
    }
}

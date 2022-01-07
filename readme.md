# Laravel Proxy Scheme

This package will check if a request has a `X-Forwarded-Proto` header 
and force the `UrlGenerator` to use the scheme in the header.

It is useful when an app is served behind a proxy that has SSL enabled.

## Installation

```
composer require rodrigopedra/laravel-proxy-scheme
```

### License

This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

Laravel Javascript Routes
=========================

[![Travis Badge](https://secure.travis-ci.org/fedeisas/laravel-js-routes.png)](http://travis-ci.org/fedeisas/laravel-js-routes)
[![Coverage Status](https://coveralls.io/repos/fedeisas/laravel-js-routes/badge.png)](https://coveralls.io/r/fedeisas/laravel-js-routes)
[![Latest Stable Version](https://poser.pugx.org/fedeisas/laravel-js-routes/v/stable.png)](https://packagist.org/packages/fedeisas/laravel-js-routes)
[![Latest Unstable Version](https://poser.pugx.org/fedeisas/laravel-js-routes/v/unstable.png)](https://packagist.org/packages/fedeisas/laravel-js-routes)
[![Total Downloads](https://poser.pugx.org/fedeisas/laravel-js-routes/downloads.png)](https://packagist.org/packages/fedeisas/laravel-js-routes)
[![License](https://poser.pugx.org/fedeisas/laravel-js-routes/license.png)](https://packagist.org/packages/fedeisas/laravel-js-routes)

## Why?
I love the Laravel 4 routing system and I often use named routes like `route('users.show', array('id' => 1))` to generate `http://domain.tld/users/1`.
With the amazing uprising of Javascript frameworks (AngularJS, EmberJS, Backbone, etc.) it's hard to track changes on your routes between the backend and the REST calls from your Javascript.
The goal of this library is to expose those named routes to your frontend so you can do: `Router.route('users.show', {id: 1})` and get the same result.

## Requirements
- Laravel **4.1**

## Installation

Begin by installing this package through Composer. Edit your project's `composer.json` file to require `fedeisas/laravel-js-routes`.

```json
{
  "require": {
        "laravel/framework": "4.0.*",
        "fedeisas/laravel-js-routes": "1.*"
    },
    "minimum-stability" : "dev"
}
```

Next, update Composer from the Terminal:
```bash
$ composer update
```

Once this operation completes, the final step is to add the service provider. Open `app/config/app.php`, and add a new item to the providers array.
```php
'Fedeisas\LaravelJsRoutes\LaravelJsRoutesServiceProvider',
```

## Usage
By default the command will generate a `routes.js` file on your project root. This contains all **named** routes in your app.
That's it! You're all set to go. Run the `artisan` command from the Terminal to see the new `routes:javascript` commands.
```bash
$ php artisan routes:javascript
```
> **Lazy Tip** If you use Grunt, you could set up a watcher that runs this command whenever your routes files change.

## Arguments
| Name     | Default     | Description     |
| -------- |:-----------:| --------------- |
| **name** | *routes.js* | Output filename |

## Options
| Name     | Default     | Description     |
| -------- |:-----------:| --------------- |
| **path**   | *base_path()* | Where to save the generated filename. (ie. public assets folder) |
| **filter** | *null*        | If you want only some routes to be available on JS, you can use a filter (like js-routable) to select only those |
| **object** | *Router*      | If you want to choose your own global JS object (to avoid collision) |

## Javascript usage
You have to include the generated file in your views (or your assets build process).
```html
<script src="/path/to/routes.js" type="text/javascript">
```

And then you have a `Routes` object on your global scope. You can use it as:
```javascript
Router.route(route_name, params)
```

Example:
```javascript
Router.route('users.show', {id: 1}) // returns http://dommain.tld/users/1
```

If you assign parameters that are not present on the URI, they will get appended as a query string:
```javascript
Router.route('users.show', {id: 1, name: 'John', order: 'asc'}) // returns http://dommain.tld/users/1?name=John&order=asc
```

## Contributing
```bash
$ composer install --dev
$ ./vendor/bin/phpunit
```

```bash
$ npm install -g grunt-cli
$ npm install
$ grunt travis --verbose
```

In addition to a full test suite, there is Travis integration.

## Found a bug?
Please, let me know! Send a pull request or a patch. Questions? Ask! I will respond to all filed issues.

## Inspiration
Although no code was copied, this package is greatly inspired by [FOSJsRoutingBundle](https://github.com/FriendsOfSymfony/FOSJsRoutingBundle) for Symfony.

## License
This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

# Router

Really simple right now... Need to add a notion of named routes. Right now routes are identified by their arguments.

## Usage
```php

use Router\Router;

$router = new Router();
$router->register('user', "/users/{{username|text}}");
$router->register('file', "/files/{{filename}}");

$routeData = $router->route('user', "/users/oranj");
// $routeData: ['username' => 'oranj']

echo $router->build('user', ['username' => 'oranj']).PHP_EOL;
// /users/oranj

echo $router->build('file', ['filename' => 'README.md']).PHP_EOL;
// /files/README.md

```

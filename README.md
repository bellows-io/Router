# Router

Really simple right now... Need to add a notion of named routes. Right now routes are identified by their arguments.

## Usage
```php

use Router\Router;

$router = new Router();
$router->register("/users/{{username|text}}");
$router->register("/files/{{filename}}");

$routeData = $router->route("/users/oranj");
// $routeData: ['username' => 'oranj']

echo $router->build(['username' => 'oranj']).PHP_EOL;
// /users/oranj

echo $router->build(['filename' => 'README.md']).PHP_EOL;
// /files/README.md

```
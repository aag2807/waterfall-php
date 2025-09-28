# Waterfall 🌊

A lightweight PHP framework that mimics Spring Boot's annotation-based routing system using PHP 8+ attributes (decorators).

## Features

- **Attribute-based routing** - Use decorators like `#[Controller]` and `#[Get]` just like Spring Boot
- **Automatic controller discovery** - Controllers are automatically registered and mapped
- **HTTP method support** - GET, POST, PUT, PATCH, DELETE decorators
- **Clean routing** - Simple, intuitive route definitions
- **Zero configuration** - Just add attributes and go

## Requirements

- PHP 8.0 or higher
- Web server with URL rewriting support

## Quick Start

### 1. Create a Controller

```php
<?php

namespace Waterfall\Controllers;

use Waterfall\Utils\Decorators\Controller;
use Waterfall\Utils\Decorators\Get;
use Waterfall\Utils\Decorators\Post;

#[Controller("/api/users")]
class UserController
{
    #[Get("/")]
    public function getAllUsers()
    {
        return "Getting all users";
    }

    #[Get("/{id}")]
    public function getUserById()
    {
        return "Getting user by ID";
    }

    #[Post("/")]
    public function createUser()
    {
        return "Creating new user";
    }
}
```

### 2. Register Controllers and Start Listening

```php
<?php

require_once 'vendor/autoload.php';

use Waterfall\Utils\ControllerRegistry;
use Waterfall\Utils\RouterConfig;

// Register all controllers
ControllerRegistry::registerControllers();

// Start listening for requests
RouterConfig::listen();
```

## Available Decorators

### Controller Decorator
Defines the base route for a controller class:

```php
#[Controller("/api/products")]
class ProductController { }
```

### HTTP Method Decorators

- `#[Get("/path")]` - Handle GET requests
- `#[Post("/path")]` - Handle POST requests
- `#[Put("/path")]` - Handle PUT requests
- `#[Patch("/path")]` - Handle PATCH requests
- `#[Delete("/path")]` - Handle DELETE requests

## Route Examples

```php
#[Controller("/home")]
class HomeController
{
    #[Get("/")]           // Matches: GET /home
    public function index() { }

    #[Get("/about")]      // Matches: GET /home/about
    public function about() { }
}

#[Controller("/api")]
class ApiController
{
    #[Post("/users")]     // Matches: POST /api/users
    public function createUser() { }

    #[Delete("/users")]   // Matches: DELETE /api/users
    public function deleteUser() { }
}
```

## Project Structure

```
waterfall/
├── src/
│   ├── Controllers/           # Your controller classes
│   │   └── HomeController.php
│   └── Utils/
│       ├── Decorators/        # Attribute classes
│       │   ├── Controller.php
│       │   ├── Get.php
│       │   ├── Post.php
│       │   ├── Put.php
│       │   ├── Patch.php
│       │   └── Delete.php
│       ├── ControllerRegistry.php  # Auto-discovery
│       └── RouterConfig.php        # Route matching
├── public/
│   └── index.php             # Entry point
└── README.md
```

## How It Works

1. **Discovery**: `ControllerRegistry` scans the Controllers directory for classes
2. **Registration**: Reads controller and method attributes to build route mappings
3. **Routing**: `RouterConfig` matches incoming requests to registered routes
4. **Execution**: Instantiates the controller and invokes the matched method

## Inspiration

Waterfall is inspired by Spring Boot's clean annotation-based routing system, bringing that developer experience to PHP with native attributes introduced in PHP 8.

## Contributing

Feel free to submit issues and enhancement requests!

## License

MIT License - see LICENSE file for details.
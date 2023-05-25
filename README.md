# Simple API

A simple REST/CRUD API built using PHP.

## Requirements

+ PHP version 8.1 or later

## Example ```index.php```

```php
<?php

namespace APIExample;

use AOWD\SimpleAPI\Methods;
use AOWD\SimpleAPI\Status;
use AOWD\SimpleAPI\API;

// Load dependencies
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Register API modules loaded action
API::registerAction('modules_loaded', '\APIExample\registerRoutes');

/**
 * Register API routes
 * @return void
 */
function registerRoutes(): void
{
    // Register index page GET request
    API::registerRoute(Methods::GET, '/api/', '\APIExample\home');
}

/**
 * Process API home endpoint
 * @return never
 */
function home(): never
{
    API::sendMessage(
        messages: ['Success'],
        status: Status::Success,
        response_code: 200
    );
}

// Run API
API::init();
```

### 200 Success Result:

```json
{
    "success": [
        "Success"
    ]
}
```

### Error 404

```json
{
    "fail": [
        "Route not found"
    ]
}
```
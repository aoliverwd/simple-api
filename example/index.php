<?php

namespace APIExample;

use AOWD\SimpleAPI\Methods;
use AOWD\SimpleAPI\Status;
use AOWD\SimpleAPI\API;

// Load dependencies
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Load API class
require_once dirname(__DIR__) . '/src/api.php';

// Register API modules loaded action
API::registerAction('modules_loaded', '\APIExample\registerRoutes');

/**
 * Register API routes
 * @return void
 */
function registerRoutes(): void
{
    API::registerRoute(Methods::GET, '/', '\APIExample\home');
}

/**
 * Process API home endpoint
 * @return never
 */
function home(): never
{
    API::sendMessage(
        messages: ['Awesome'],
        status: Status::Success,
        response_code: 200
    );
}

// Run API
API::init();

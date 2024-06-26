<?php

// Set namespace
namespace AOWD\SimpleAPI;

use Spatie\Url\Url;
use Bramus\Router;

// Load enumerations
require_once __DIR__ . '/enumerations.php';

// API core class
class API
{
    /**
     * Registered actions
     * @var array<mixed>
     */
    protected static array $actions = [];

    /**
     * Routes
     * @var object
     */
    protected static object $routes;

    /**
     * Registered route count
     * @var integer
     */
    private static int $route_count = 0;

    /**
     * URL Segments
     * @var object
     */
    private static object $url_segments;

    /**
     * Instantiate HTTP
     * @return void
     */
    public static function init(): void
    {
        // Instantiate router
        self::$routes = new Router\Router();

        // Include modules action
        self::doAction('include_modules');

        // Run modules loaded action
        self::doAction('modules_loaded');

        // Run routes
        if (method_exists(self::$routes, 'run') && self::$route_count > 0) {
            // Set URL segments
            self::setURL($_SERVER['REQUEST_URI']);

            // Before route run
            self::doAction('before_route_run');

            // Run route
            self::$routes->run();
        }

        // Route not found 404
        self::sendMessage(
            messages: ['Route not found'],
            status: Status::Fail,
            response_code: 404
        );
    }

    /**
     * Set URL
     * @param string $url
     */
    public static function setURL(string $url): void
    {
        self::$url_segments = Url::fromString($url);
    }

    /**
     * Register action
     * @param  string $action_name
     * @param  string $callback
     * @return void
     */
    public static function registerAction(string $action_name, string $callback): void
    {
        if (is_callable($callback)) {
            if (!isset(self::$actions[$action_name])) {
                self::$actions[$action_name] = [];
            }

            if (is_array(self::$actions[$action_name])) {
                self::$actions[$action_name][] = $callback;
            }
        }
    }

    /**
     * Do action
     * @param  string $action_name
     * @param  array<mixed> $args
     * @return void
     */
    public static function doAction(string $action_name, array $args = []): void
    {
        if (isset(self::$actions[$action_name]) && is_array(self::$actions[$action_name])) {
            foreach (self::$actions[$action_name] as $callable) {
                call_user_func($callable, $args);
            }
        }
    }

    /**
     * Register route
     * @param  Methods $method
     * @param  string  $pattern
     * @param  string  $callback
     * @return void
     */
    public static function registerRoute(Methods $method, string $pattern, string $callback): void
    {
        if (method_exists(self::$routes, $method->value) && method_exists(self::$routes, 'match')) {
            self::$routes->match($method->name, $pattern, $callback);
            self::$route_count += 1;
        }
    }

    /**
     * Send Message
     * @param  array<mixed>       $messages
     * @param  array<mixed>       $data
     * @param  Status $status
     * @param  int|integer $response_code
     * @return never
     */
    public static function sendMessage(array $messages, Status $status, array $data = [], int $response_code = 200): never
    {
        $body = [
            $status->value => $messages,
        ];

        if (count($data) > 0) {
            $body['data'] = $data;
        }

        // Run before message display
        self::doAction('before_message_display', [
            'response_code' => $response_code,
            'messages' => $messages,
            'status' => $status,
            'data' => $data
        ]);

        http_response_code($response_code);

        switch (self::getContentType()) {
            case 'text':
                header('Content-Type: text/plain');
                echo implode('', $messages);
                die;
            case 'html':
                header('Content-Type: text/html');
                echo implode('', $messages);
                die;
            default:
                header('Content-Type: application/json');
                die(json_encode($body));
        }
    }

    /**
     * Get content type
     * @return string
     */
    public static function getContentType(): string
    {
        $type = (string) self::getQuery('content_type');
        return empty($type) ? 'json' : $type;
    }

    /**
     * Get URL query string or singular parameter
     * @param  string $parameter
     * @param  string $challenge
     * @return int|string|null
     */
    public static function getQuery(string $parameter = '', string $challenge = ''): int|string|null
    {
        if (method_exists(self::$url_segments, 'getQuery')) {
            if (!empty($parameter) && method_exists(self::$url_segments, 'getQueryParameter')) {
                return !empty($challenge) ? self::$url_segments->getQueryParameter($parameter, $challenge) : self::$url_segments->getQueryParameter($parameter);
            }

            return self::$url_segments->getQuery();
        }

        return '';
    }

    /**
     * Get URL segment
     * @param  int         $segmentIndex
     * @param  URLSegments $type [Index, Start, End]
     * @return int|string|null
     */
    public static function getURLSegment(int $segmentIndex = 1, URLSegments $type = URLSegments::Index): int|string|null
    {
        return match ($type) {
            URLSegments::Index => method_exists(self::$url_segments, 'getSegment') ? self::$url_segments->getSegment($segmentIndex) : null,
            URLSegments::Start => method_exists(self::$url_segments, 'getFirstSegment') ? self::$url_segments->getFirstSegment($segmentIndex) : null,
            URLSegments::End => method_exists(self::$url_segments, 'getLastSegment') ? self::$url_segments->getLastSegment($segmentIndex) : null
        };
    }
}

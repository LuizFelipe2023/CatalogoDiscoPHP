<?php

require_once __DIR__ . '/Auth.php';

class Router
{
    public static function route(string $methodExpected, string $uriExpected, $action, bool $auth = false, bool $adminOnly = false)
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        $uri = ($uri !== '/') ? rtrim($uri, '/') : '/';
        $uriExpected = ($uriExpected !== '/') ? rtrim($uriExpected, '/') : '/';

        if ($method === $methodExpected && $uri === $uriExpected) {

            if ($auth && !$adminOnly) {
                Auth::requireLogin();
            }

            if ($adminOnly) {
                Auth::requireAdmin();
            }

            call_user_func($action);
            exit;
        }
    }

    public static function match(string $methodExpected, string $pattern, $action, bool $auth = false, bool $adminOnly = false)
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = ($uri !== '/') ? rtrim($uri, '/') : '/';

        if ($method === $methodExpected && preg_match($pattern, $uri, $matches)) {

            if ($auth && !$adminOnly) {
                Auth::requireLogin();
            }

            if ($adminOnly) {
                Auth::requireAdmin();
            }

            array_shift($matches);
            call_user_func_array($action, $matches);
            exit;
        }
    }
}
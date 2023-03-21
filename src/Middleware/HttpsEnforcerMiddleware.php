<?php
declare(strict_types=1);

namespace Security\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Enhanced version of CakePHP's built-in HttpsEnforcer middleware
 *
 * This version adds following configuration options:
 * - disableForHosts: (string) Comma-separated list of
 */
class HttpsEnforcerMiddleware extends \Cake\Http\Middleware\HttpsEnforcerMiddleware
{
    public function __construct(array $config = [])
    {
        $this->config += ['disableForHosts' => null];
        parent::__construct($config);
    }

    /**
     * Process method.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Server\RequestHandlerInterface $handler The request handler.
     * @return \Psr\Http\Message\ResponseInterface A response.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $allowedHosts = $this->config['disableForHosts'] ?? '';
        if (is_string($allowedHosts)) {
            $allowedHosts = explode(",", (string)$allowedHosts);
        }

        if ($request instanceof \Cake\Http\ServerRequest) {
            // hostname without port
            $host = strtolower($request->host());
            if (strpos($host, ":") !== false) {
                $host = substr($host, 0, strpos($host, ":"));
            }

            $filtered = array_filter((array)$allowedHosts, function ($allowed) use ($host) {
                $allowed = strtolower(trim($allowed));
                if (strpos($allowed, "*") === false && $host === $allowed) {
                    return true;
                }

                $pattern = preg_quote($allowed, '/');
                $pattern = str_replace("\*", "(.*)", $pattern);
                $pattern = sprintf("/^%s$/", $pattern);
                if (preg_match($pattern, $host)) {
                    return true;
                }

                return false;
            });

            // skip https enforcement if hostname is in allowed hosts.
            if (count($filtered) > 0) {
                return $handler->handle($request);
            }
        }

        return parent::process($request, $handler);
    }
}

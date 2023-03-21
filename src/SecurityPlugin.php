<?php
declare(strict_types=1);

namespace Security;

use Cake\Console\CommandCollection;
use Cake\Core\BasePlugin;
use Cake\Core\Configure;
use Cake\Core\ContainerInterface;
use Cake\Core\Plugin;
use Cake\Core\PluginApplicationInterface;
use Cake\Event\EventListenerInterface;
use Cake\Event\EventManager;
use Cake\Http\Middleware\CsrfProtectionMiddleware;
//use Cake\Http\Middleware\HttpsEnforcerMiddleware;
use Cake\Http\Middleware\SecurityHeadersMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\RouteBuilder;
use Psr\Http\Server\MiddlewareInterface;

/**
 * Plugin for Security
 */
class SecurityPlugin extends BasePlugin implements EventListenerInterface
{
    /**
     * Load all the plugin configuration and bootstrap logic.
     *
     * The host application is provided as an argument. This allows you to load
     * additional plugin dependencies, or attach events.
     *
     * @param \Cake\Core\PluginApplicationInterface $app The host application
     * @return void
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        Configure::load('Security.security');
        if (Plugin::isLoaded('Settings')) {
            Configure::load('Security', 'settings');
        }

        EventManager::instance()->on($this);
    }

    /**
     * Add routes for the plugin.
     *
     * If your plugin has many routes and you would like to isolate them into a separate file,
     * you can create `$plugin/config/routes.php` and delete this method.
     *
     * @param \Cake\Routing\RouteBuilder $routes The route builder to update.
     * @return void
     */
    public function routes(RouteBuilder $routes): void
    {
        $routes->plugin(
            'Security',
            ['path' => '/security'],
            function (RouteBuilder $builder) {
                // Add custom routes here

                $builder->fallbacks();
            }
        );
        parent::routes($routes);
    }

    /**
     * Add middleware for the plugin.
     *
     * @param \Cake\Http\MiddlewareQueue $middlewareQueue The middleware queue to update.
     * @return \Cake\Http\MiddlewareQueue
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        if (Configure::read('Security.Headers.enabled')) {
            $middlewareQueue = $middlewareQueue->add($this->buildSecurityHeadersMiddleware());
        }
        if (Configure::read('Security.CSRF.enabled')) {
            $middlewareQueue = $middlewareQueue->add($this->buildCsrfMiddleware());
        }
        if (Configure::read('Security.HttpsEnforcer.enabled')) {
            $middlewareQueue = $middlewareQueue->add($this->buildHttpsEnforcerMiddleware());
        }

        return $middlewareQueue;
    }

    /**
     * Add commands for the plugin.
     *
     * @param \Cake\Console\CommandCollection $commands The command collection to update.
     * @return \Cake\Console\CommandCollection
     */
    public function console(CommandCollection $commands): CommandCollection
    {
        $commands = parent::console($commands);

        return $commands;
    }

    /**
     * Register application container services.
     *
     * @param \Cake\Core\ContainerInterface $container The Container to update.
     * @return void
     * @link https://book.cakephp.org/4/en/development/dependency-injection.html#dependency-injection
     */
    public function services(ContainerInterface $container): void
    {
    }

    /**
     * @inheritDoc
     */
    public function implementedEvents(): array
    {
        return [];
    }

    /**
     * @return MiddlewareInterface
     */
    protected function buildSecurityHeadersMiddleware(): MiddlewareInterface
    {
        $securityHeaders = new SecurityHeadersMiddleware();
        $securityHeaders
            ->setCrossDomainPolicy()
            ->setReferrerPolicy()
            ->setXFrameOptions()
            ->setXssProtection()
            ->noOpen()
            ->noSniff();

        return $securityHeaders;
    }

    protected function buildCsrfMiddleware(): MiddlewareInterface
    {
        $options = [];
        return new CsrfProtectionMiddleware($options);
    }

    protected function buildHttpsEnforcerMiddleware(): MiddlewareInterface
    {
        $options = [
            'redirect' => (bool)Configure::read('Security.HttpsEnforcer.redirect', false),
            'statusCode' => (int)Configure::read('Security.HttpsEnforcer.statusCode', 302),
            'disableOnDebug' => (bool)Configure::read('Security.HttpsEnforcer.disableOnDebug', false),
            'disableForHosts' => Configure::read('Security.HttpsEnforcer.disableForHosts'),
        ];
        if (!in_array($options['statusCode'], [301, 302])) {
            $options['statusCode'] = 302;
        }

        // additional headers
        $headers = [];
        if (Configure::read('debug')) {
            $headers['X-App-Security'] = 1;
        }
        if (Configure::read('Security.HttpsEnforcer.sendUpgradeHeader')) {
            $headers['X-Https-Upgrade'] = 1;
        }
        $options['headers'] = $headers;


        // hsts
        if (Configure::read('Security.HttpsEnforcer.hstsEnabled')) {
            $hsts = [
                // How long the header value should be cached for.
                'maxAge' => (int)Configure::read('Security.HttpsEnforcer.hstsMaxAge', 60 * 60 * 24 * 365),
                // should this policy apply to subdomains?
                'includeSubDomains' => (bool)Configure::read('Security.HttpsEnforcer.hstsIncludeSubdomains', false),
                // Should the header value be cacheable in google's HSTS preload
                // service? While not part of the spec it is widely implemented.
                'preload' => (bool)Configure::read('Security.HttpsEnforcer.hstsIncludeSubdomains', false),
            ];
            $options['hsts'] = $hsts;
        }

        // using own implementation which extends the CakePHP's built in HttpsEnforcerMiddleware
        return new \Security\Middleware\HttpsEnforcerMiddleware($options);
    }
}

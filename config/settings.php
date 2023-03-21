<?php

use Cake\Http\Middleware\SecurityHeadersMiddleware;

return [
    'Settings' => [
        'Security' => [
            'groups' => [
                'Security.SSL' => [
                    'label' => __d('admin', 'SSL/HTTPS'),
                ],
                'Security.HttpsEnforcer' => [
                    'label' => __d('admin', 'Https Enforcer'),
                ],
                'Security.HSTS' => [
                    'label' => __d('admin', 'Strict Transport Security (HSTS)'),
                ],
                'Security.Headers' => [
                    'label' => __d('admin', 'HTTP Headers'),
                ],
                'Security.CSRF' => [
                    'label' => __d('admin', 'CSRF'),
                ],
                'Security.CSP' => [
                    'label' => __d('admin', 'Content Security Policy'),
                ],
                'Security.FormProtection' => [
                    'label' => __d('admin', 'Form Tampering Protection'),
                ],
            ],

            'schema' => [
                // General
                'Security.enabled' => [
                    'group' => 'Security.General',
                    'type' => 'boolean',
                    'label' => __d('admin', 'Enable Security'),
                    'help' => __d('admin', 'Enable or disable all security features provided by this plugin'),
                    'default' => false,
                ],


                // Form Protection
                'Security.FormProtection.enabled' => [
                    'group' => 'Security.FormProtection',
                    'type' => 'boolean',
                    'label' => __d('admin', 'Enable Form Protection'),
                    'help' => __d('admin', 'The Form Protection Component provides protection against form data tampering.'),
                    'default' => false,
                ],


                // CSRF
                'Security.CSRF.enabled' => [
                    'group' => 'Security.CSRF',
                    'type' => 'boolean',
                    'label' => __d('admin', 'Enable CSRF Protection'),
                    'help' => __d('admin', 'Cross-Site Request Forgeries (CSRF) are a class of exploit where unauthorized commands are performed on behalf of an authenticated user without their knowledge or consent.'),
                    'default' => false,
                ],


                // CSP
                'Security.CSP.enabled' => [
                    'group' => 'Security.CSP',
                    'type' => 'boolean',
                    'label' => __d('admin', 'Enable Content Security Policy'),
                    'default' => false,
                ],

                // HTTP Headers
                'Security.Headers.enabled' => [
                    'group' => 'Security.Headers',
                    'type' => 'boolean',
                    'help' => __d('admin', 'Enable/Disable following HTTP Header security rules'),
                    'default' => false,
                ],
                'Security.Headers.setCrossDomainPolicy' => [
                    'group' => 'Security.Headers',
                    'type' => 'boolean',
                    'default' => false,
                ],
                'Security.Headers.setReferrerPolicy' => [
                    'group' => 'Security.Headers',
                    'type' => 'boolean',
                    'default' => false,
                ],
                'Security.Headers.referrerPolicy' => [
                    'group' => 'Security.Headers',
                    'type' => 'string',
                    'input' => [
                        'type' => 'select',
                        'empty' => true,
                        'options' => function () {
                            $available = [
                                SecurityHeadersMiddleware::NO_REFERRER,
                                SecurityHeadersMiddleware::NO_REFERRER_WHEN_DOWNGRADE,
                                SecurityHeadersMiddleware::ORIGIN,
                                SecurityHeadersMiddleware::ORIGIN_WHEN_CROSS_ORIGIN,
                                SecurityHeadersMiddleware::SAME_ORIGIN,
                                SecurityHeadersMiddleware::STRICT_ORIGIN,
                                SecurityHeadersMiddleware::STRICT_ORIGIN_WHEN_CROSS_ORIGIN,
                                SecurityHeadersMiddleware::UNSAFE_URL,
                            ];
                            return array_combine($available, $available);
                        }
                    ],
                    //'default' => SecurityHeadersMiddleware::SAME_ORIGIN,
                    'default' => null,
                    'help' => __('Defaults to same-origin')
                ],
                'Security.Headers.setXFrameOptions' => [
                    'group' => 'Security.Headers',
                    'type' => 'boolean',
                    'default' => false,
                ],
                'Security.Headers.setXssProtection' => [
                    'group' => 'Security.Headers',
                    'type' => 'boolean',
                    'default' => false,
                ],
                'Security.Headers.setNoOpen' => [
                    'group' => 'Security.Headers',
                    'type' => 'boolean',
                    'default' => false,
                ],
                'Security.Headers.setNoSniff' => [
                    'group' => 'Security.Headers',
                    'type' => 'boolean',
                    'default' => false,
                ],


                // SSL
                'Security.SSL.enabled' => [
                    'group' => 'Security.SSL',
                    'type' => 'boolean',
                    'label' => __d('admin', 'Enable SSL'),
                    'help' => __d('admin', 'Enable or disable SSL usage in your application'),
                    'default' => false,
                ],

                // HTTPS Enforcer
                'Security.HttpsEnforcer.enabled' => [
                    'group' => 'Security.HttpsEnforcer',
                    'type' => 'boolean',
                    'label' => __d('admin', 'Enforce HTTPS'),
                    'help' => __d('admin', 'HTTP url will automatically redirected/rewritten to HTTPS url scheme'),
                    'default' => false,
                ],
                'Security.HttpsEnforcer.redirect' => [
                    'group' => 'Security.HttpsEnforcer',
                    'type' => 'boolean',
                    'label' => __d('admin', 'Redirect'),
                    'help' => __d('admin', 'If disabled, always raises an exception and never redirects.'),
                    'default' => true,
                ],
                'Security.HttpsEnforcer.statusCode' => [
                    'group' => 'Security.HttpsEnforcer',
                    'type' => 'number',
                    'label' => __d('admin', 'Redirect status code'),
                    'help' => __d('admin', 'Defaults to a temporary redirect (HTTP status 302)'),
                    'default' => 301,
                    'input' => [
                        'type' => 'select',
                        'empty' => true,
                        'options' => [
                            301 => __('301: Permanent redirect'),
                            302 => __('302: Temporary redirect'),
                        ]
                    ],
                ],
                'Security.HttpsEnforcer.sendUpgradeHeader' => [
                    'group' => 'Security.HttpsEnforcer',
                    'type' => 'boolean',
                    'label' => __d('admin', 'Enable X-Https-Upgrade'),
                    'help' => __d('admin', 'Adds X-Https-Upgrade to HTTP header'),
                    'default' => true,
                ],
                'Security.HttpsEnforcer.disableOnDebug' => [
                    'group' => 'Security.HttpsEnforcer',
                    'type' => 'boolean',
                    'label' => __d('admin', 'Disable in DEBUG mode'),
                    'help' => __d('admin', 'Disable HTTPs enforcement when ``debug`` is on.'),
                    'default' => true,
                ],
                'Security.HttpsEnforcer.disableForHosts' => [
                    'group' => 'Security.HttpsEnforcer',
                    'type' => 'string',
                    'label' => __d('admin', 'Disable for hosts'),
                    'help' => __d('admin', 'Disable HTTPs enforcement for these hostnames. Comma-separated list of hosts names.'),
                    'default' => '127.0.0.1,::1,localhost,*.local',
                ],

                // HSTS
                'Security.HttpsEnforcer.hstsEnabled' => [
                    'group' => 'Security.HSTS',
                    'type' => 'boolean',
                    'label' => __d('admin', 'HSTS: Enable Strict Transport Security'),
                    'help' => __d('admin', 'When your application requires SSL it is a good idea to set the Strict-Transport-Security header. This header value is cached in the browser, and informs browsers that they should always connect with HTTPS connections.'),
                    'default' => false,
                ],
                'Security.HttpsEnforcer.hstsMaxAge' => [
                    'group' => 'Security.HSTS',
                    'type' => 'number',
                    'label' => __d('admin', 'HSTS: Max Age Seconds'),
                    'help' => __d('admin', 'Number of seconds the header value should be cached for.'),
                    'default' => 60 * 60 * 24, // * 365,
                ],
                'Security.HttpsEnforcer.hstsIncludeSubdomains' => [
                    'group' => 'Security.HSTS',
                    'type' => 'boolean',
                    'label' => __d('admin', 'HSTS: Include Subdomains'),
                    'help' => __d('admin', 'Should this policy apply to subdomains?'),
                    'default' => false,
                ],
                'Security.HttpsEnforcer.hstsPreload' => [
                    'group' => 'Security.HSTS',
                    'type' => 'boolean',
                    'label' => __d('admin', 'HSTS: Preload'),
                    'help' => __d('admin', 'Should the header value be cacheable in google\'s HSTS preload service? While not part of the spec it is widely implemented.'),
                    'default' => false,
                ],
            ],
        ],
    ],
];

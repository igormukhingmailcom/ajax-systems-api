<?php

namespace Mukhin\AjaxSystemsApi;

use Http\Client\HttpClient;
use Http\Client\Common\Plugin;
use Http\Client\Common\PluginClient;
use Http\Client\Common\Plugin\CookiePlugin;
use Http\Discovery\HttpClientDiscovery;
use Http\Message\CookieJar;

/**
 * Class HttpClientFactory
 * @package Mukhin\AjaxSystemsApi
 */
class HttpClientFactory
{
    /**
     * Build the HTTP client with plugins required by library
     *
     * @param Plugin[]   $plugins List of additional plugins to use
     * @param HttpClient $client  Base HTTP client
     *
     * @return HttpClient
     */
    public static function create(array $plugins = [], HttpClient $client = null)
    {
        if (!$client) {
            $client = HttpClientDiscovery::find();
        }

        $plugins[] = new CookiePlugin(new CookieJar());

        return new PluginClient($client, $plugins);
    }
}
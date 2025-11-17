<?php

use mako\application\Application;
use mako\application\CurrentApplication;
use mako\gatekeeper\adapters\AdapterInterface;
use mako\http\routing\URLBuilder;
use mako\gatekeeper\Gatekeeper;
use mako\syringe\Container;
use mako\session\Session;
use mako\http\Request;


// Application
if (function_exists('app') === false) {
	/**
	 * Returns the application instance.
	 * @return \mako\application\Application
	 */
	function app(): Application
	{
		static $app;

		if ($app === null) {
			$app = CurrentApplication::get();
		}

		return $app;
	}
}

// Container
if (function_exists('container') === false) {
	/**
	 * Returns the container instance.
	 * @return \mako\syringe\Container
	 */
	function container(): Container
	{
		static $container;

		if ($container === null) {
			$container = app()->getContainer();
		}

		return $container;
	}
}

// Config
if (function_exists('config') === false) {
	/**
	 * Returns the config instance.
	 * @param  array|string|null  $key
	 * @param  mixed  $default
	 * @return mixed|\mako\config\Config
	 */
	function config($key = null, $default = null)
	{
		static $config;

		if ($config === null) {
			$config = app()->getConfig();
		}

		if (is_null($key)) {
			return $config;
		}

		if (is_array($key)) {
			return $config->set($key);
		}

		return $config->get($key, $default);
	}
}

// Request
if (function_exists('request') === false) {
	/**
	 * Returns the request instance.
	 * @return \mako\http\Request
	 */
	function request(): Request
	{
		static $request;

		if ($request === null) {
			$request = container()->get(Request::class);
		}

		return $request;
	}

	if (function_exists('turbo_frame') === false) {
		/**
		 * Gets a Turbo-Frame header.
		 * @return mixed
		 */
		function turbo_frame()
		{
			return request()->getHeaders()->get('Turbo-Frame');
		}
	}

	if (function_exists('cookie') === false) {
		/**
		 * Gets a cookie value.
		 * @param  string $name    Cookie name
		 * @param  mixed  $default Default value
		 * @return mixed
		 */
		function cookie(string $name, $default = null)
		{
			return request()->getCookies()->get($name, $default);
		}
	}

	if (function_exists('signed_cookie') === false) {
		/**
		 * Gets a signed cookie value.
		 * @param  string $name    Cookie name
		 * @param  mixed  $default Default value
		 * @return mixed
		 */
		function signed_cookie(string $name, $default = null)
		{
			return request()->getCookies()->getSigned($name, $default);
		}
	}
}

// UrlBuilder
if (function_exists('url') === false) {
	/**
	 * Returns the url builder instance.
	 * @return \mako\http\routing\URLBuilder
	 */
	function url(): URLBuilder
	{
		static $url;

		if ($url === null) {
			$url = container()->get(URLBuilder::class);
		}

		return $url;
	}

	if (function_exists('route') === false) {
		/**
		 * Returns the URL of a named route.
		 * @param  string      $route       Route name
		 * @param  array       $params      Route parameters
		 * @param  array       $query       Associative array used to build URL-encoded query string
		 * @param  string      $separator   Argument separator
		 * @param  bool|string $language    Request language
		 * @return string
		 */
		function route(
			string $route, array $params = [], array $query = [],
			string $separator = '&', bool|string $language = true
		): string
		{
			return url()->toRoute(
				$route, $params, $query, $separator, $language
			);
		}
	}
}

// Session
if (function_exists('session') === false) {
	/**
	 * Returns the session instance.
	 * @return \mako\session\Session
	 */
	function session(): Session
	{
		static $session;

		if ($session === null) {
			$session = container()->get(Session::class);
		}

		return $session;
	}

	if (function_exists('flashdata') === false) {
		/**
		 * Returns a flash data from the session.
		 * @return array
		 */
		function flashdata()
		{
			return session()->get('mako.flashdata', []);
		}
	}

	if (function_exists('flash') === false) {
		/**
		 * Returns a flash value from the session.
		 * @param  string $key     Session key
		 * @param  mixed  $default Default value
		 * @return mixed
		 */
		function flash(string $key, $default = null)
		{
			return session()->getFlash($key, $default);
		}
	}

	if (function_exists('token') === false) {
		/**
		 * Returns the session token.
		 * @return string
		 */
		function token(): string
		{
			return session()->getToken();
		}
	}

	if (function_exists('one_time_token') === false) {
		/**
		 * Returns a random security token.
		 * @return string
		 */
		function one_time_token(): string
		{
			return session()->generateOneTimeToken();
		}
	}
}

// Gatekeeper
if (function_exists('gatekeeper') === false) {
	/**
	 * Returns a gatekeeper adapter instance.
	 * @param  string|null       $adapterName Adapter name
	 * @return \mako\gatekeeper\adapters\AdapterInterface
	 */
	function gatekeeper(?string $adapterName = null): AdapterInterface
	{
		static $gatekeeper;

		if ($gatekeeper === null) {
			$gatekeeper = container()->get(Gatekeeper::class);
		}

		return $gatekeeper->adapter($adapterName);
	}

	if (function_exists('user') === false) {
		/**
		 * Returns the active user or null if there isn't one.
		 * @param  string|null                  $adapterName Adapter name
		 * @return \mako\gatekeeper\entities\user\UserEntityInterface|null
		 */
		function user(?string $adapterName = null)
		{
			return gatekeeper($adapterName)->getUser();
		}
	}
}

// Vite
if (function_exists('vite') === false) {
	/**
	 * Get the path to a versioned Vite file.
	 * @param  string  $path
	 * @param  string  $manifestDirectory
	 * @return string
	 */
	function vite($path, $manifestDirectory = 'build')
	{
		static $manifests = [];

		$publicPath = str_replace(DIRECTORY_SEPARATOR .'app', DIRECTORY_SEPARATOR .'public', MAKO_APPLICATION_PATH);
		$manifestPath = $publicPath . DIRECTORY_SEPARATOR . $manifestDirectory . DIRECTORY_SEPARATOR .'manifest.json';

		if (! isset($manifests[$manifestPath])) {
			if (! is_file($manifestPath)) {
				throw new \Exception('The manifest file does not exist.');
			}

			$manifests[$manifestPath] = json_decode(file_get_contents($manifestPath), true);
		}

		$manifest = $manifests[$manifestPath];

		if (! isset($manifest[$path])) {
			throw new \Exception("Unable to locate Vite file: {$path}.");
		}

		return '/'. $manifestDirectory .'/'. $manifest[$path]['file'];
	}
}

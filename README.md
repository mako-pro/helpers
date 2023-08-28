# Mako helpers

Helper functions for the Mako Framework

| Function                              | Description                                                  |
|---------------------------------------|--------------------------------------------------------------|
| app()                                 | Returns the `application` instance                           |
| container()                           | Returns the `mako\syringe\Container` instance                |
| config($key=null, $default=null)      | Returns the `mako\config\Config` instance                    |
| request()                             | Returns the `mako\http\Request` instance                     |
| turbo_frame()                         | Returnss the Turbo-Frame header value                        |
| cookie($name, $default=null)          | Returns the cookie value or null if the cookie doesn't exist |
| signed_cookie($name, $default=null)   | Returns the cookie value or null if the cookie doesn't exist |
| url()                                 | Returns the `mako\http\routing\URLBuilder` instance          |
| route($route, $params=[])             | Returns the URL of a named route                             |
| session()                             | Returns the `mako\session\Session` instance                  |
| flashdata()                           | Returns a flash data as array from the session               |
| flash($key, $default=null)            | Returns a flash value from the session                       |
| token()                               | Returns the session token                                    |
| one_time_token()                      | Returns a random security token                              |
| gatekeeper($adapterName=null)         | Returns a gatekeeper adapter instance                        |
| user($adapterName=null)               | Returns the active user or null if there isn't one           |
| mix($path, $manifestDirectory='')     | Returns the path to a versioned Mix file                     |

## Requirements

Mako 9.1 or greater.

## Installation

Configure composer.json:

	"require": {
		...
		"mako-pro/blade": "*"
	,
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/mako-pro/blade"
        }
    ]
	
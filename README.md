# Ontic Base ![Status](https://img.shields.io/badge/project-maintained-brightgreen.svg)

| Branch             | Build               | Coverage            | Release              |
| :----------------- | :------------------ | :------------------ | :------------------- |
| **master**         | [![Build](https://img.shields.io/travis/ontic/zend-module-base/master.svg)](https://travis-ci.org/ontic/zend-module-base)  | [![Coverage](https://img.shields.io/coveralls/ontic/zend-module-base/master.svg)](https://coveralls.io/r/ontic/zend-module-base?branch=master)   | [![Release](https://img.shields.io/packagist/v/ontic/zend-module-base.svg)](https://packagist.org/packages/ontic/zend-module-base)    | 
| **develop**        | [![Build](https://img.shields.io/travis/ontic/zend-module-base/develop.svg)](https://travis-ci.org/ontic/zend-module-base) | [![Coverage](https://img.shields.io/coveralls/ontic/zend-module-base/develop.svg)](https://coveralls.io/r/ontic/zend-module-base?branch=develop) | [![Release](https://img.shields.io/packagist/vpre/ontic/zend-module-base.svg)](https://packagist.org/packages/ontic/zend-module-base) |

## Introduction

This module provides a base set of classes which other modules can share and inherit.

## Requirements

| Name                                                                                          | Version       |
| :-------------------------------------------------------------------------------------------- | :------------ |
[PHP](https://www.php.net/)                                                                     | `>=5.5`       |
[Zend Framework 2](https://github.com/zendframework/zf2)                                        | `~2.5`        |

## Installation

We strongly suggest installing this module using [Composer](https://getcomposer.org) so that any dependencies
will get resolved and downloaded automatically. However, we've listed a few other alternatives.

### 1.1 Downloading

Download the project files as a `.zip` archive, extracting them into your `./vendor/` directory.

### 1.2 Cloning

Clone the project it into your `./vendor/` directory.

### 1.3 Composer

The easiest way to install this module is via the command line:

```
$ composer require ontic/zend-module-base:~1.0
```

Or you could manually add this module in your `composer.json` file:

```json
{
	...
	"require":
	{
		...
		"ontic/zend-module-base": "~1.0"
	}
}
```

Alternatively you could download the source by adding a repository to your `composer.json` file:

```json
{
	...
	"repositories": [
		{
			"type": "vcs",
			"url": "git@github.com:ontic/zend-module-base.git"
		}
	],
	"require":
	{
		...
		"ontic/zend-module-base": "~1.0"
	}
}
```

To download this module and its dependencies, run the command:

```
$ composer update
```

### 2.1 Enabling

Enable the module and dependencies in your `application.config.php` file.

```php
<?php
return [
	'modules' => [
		// ...
		'OnticBase',
	],
	// ...
];

```

## Documentation

Full documentation is available in the [docs](/docs) directory.

## Contributors

Below lists all individuals having contributed to the repository. If you would like to get involved, we encourage
you to do so by making a [pull](../../pulls) request or submitting an [issue](../../issues).

* [Adam Dyson](https://github.com/adamdyson)

## License

Licensed under the BSD License. See the [LICENSE](/LICENSE) file for details.

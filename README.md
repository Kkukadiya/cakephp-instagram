# Instagram plugin for CakePHP 3

[![Build Status](https://travis-ci.org/multidots/cakephp-instagram.svg?branch=master)](https://travis-ci.org/multidots/cakephp-instagram)

## Requirements

This plugin has the following requirements:

* CakePHP 3.0.0 or greater.
* PHP 5.4.16 or greater.

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

```
composer require multidots/cakephp-instagram
```

After installation, [Load the plugin](http://book.cakephp.org/3.0/en/plugins.html#loading-a-plugin)
```php
Plugin::load('Instagram', ['bootstrap' => true]);
```
Or, you can load the plugin using the shell command
```sh
$ bin/cake plugin load -b Instagram

# Manipulator Bundle

[![Build Status](https://secure.travis-ci.org/vierbergenlars/manipulator-bundle.png?branch=master)](http://travis-ci.org/vierbergenlars/manipulator-bundle)
[![Code Coverage](https://scrutinizer-ci.com/g/vierbergenlars/manipulator-bundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/vierbergenlars/manipulator-bundle/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/vierbergenlars/manipulator-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/vierbergenlars/manipulator-bundle/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/vierbergenlars/manipulator-bundle/v/stable.svg)](https://packagist.org/packages/vierbergenlars/manipulator-bundle)
[![Latest Unstable Version](https://poser.pugx.org/vierbergenlars/manipulator-bundle/v/unstable.svg)](https://packagist.org/packages/vierbergenlars/manipulator-bundle)
[![License](https://poser.pugx.org/vierbergenlars/manipulator-bundle/license.svg)](https://packagist.org/packages/vierbergenlars/manipulator-bundle)

A set of classes to make modifying Symfony services and routes easier.

## Installation

```sh
composer require vierbergenlars/manipulator-bundle
```

## Usage

All manipulators require the path to be passed in their constructor. The file must already exist and have a valid structure.

The `write()` method writes all changes to the original file.

### Modify services

Use `XmlServiceManipulator` or `YamlServiceManipulator`, depending on the type of the configuration file.

- `addService(string $id, Definition $service)`: adds a service to the configuration. Pass the service id, and the [Symfony DI Definition](http://symfony.com/doc/current/components/dependency_injection/definitions.html#working-with-a-definition)
- `removeService(string $id)`: remove a service from the configuration.

### Modify routing

Use `XmlRouteManipulator` or `YamlRouteManipulator`, depending on the type of the configuration file.

- `addRoute(string $name, array $options)`: Adds a route to the configuration.
- `removeRoute(string $name)`: Removes a route from the configuration.
- `addImport(string $resource, array $options)`: Adds an import of a resource to the configuration
- `removeImport(string $resource)`: Removes an import from the configuration.

#### `$options`

An array of configuration options for the route or import.

The options correspond to the settings structure in a routing YAML file, but they are listed below for reference.

[Have a look at the symfony documentation](http://symfony.com/doc/current/book/routing.html#creating-routes)

| Key           | Type   | Description                                          |
| ------------: | ------ | :--------------------------------------------------- |
| path          | string | Path that the route will match (route only)          |
| type          | string | Type of the import (import only)                     |
| prefix        | string | Path to prepend to imported routes (import only)     |
| host          | string | Host to match for the route(s)                       |
| schemes       | string | HTTP schemes the route(s) will respond to            |
| methods       | string | HTTP methods the route(s) will respond to            |
| defaults      | array  | Map of default settings for variables in the route   |
| requirements  | array  | Map of requirements for variables in the route       |
| options       | array  | Map of internal options for the route (rarely used)  |
| condition     | string | Custom condition that determines if the route matches|

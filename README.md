<h1 align="center">📊 Laravel Redash</h1>

<p align="center">Unleash the power of <a title="Redash" href="https://redash.io/" target="_blank">Redash.io</a> APIs in your Laravel applications. This package allows you to easily extract, analyze, and leverage your data directly within your application. Transform your data management today!</p>

<p align="center">
    <a href="https://packagist.org/packages/igorsgm/laravel-redash">
        <img src="https://img.shields.io/packagist/v/igorsgm/laravel-redash.svg?style=flat-square" alt="Latest Version on Packagist">
    </a>
    <a href="https://github.com/igorsgm/laravel-redash/actions/workflows/main.yml/badge.svg">
        <img src="https://img.shields.io/github/actions/workflow/status/igorsgm/laravel-redash/main.yml?style=flat-square" alt="Build Status">
    </a>
    <img src="https://img.shields.io/scrutinizer/coverage/g/igorsgm/laravel-redash/main?style=flat-square" alt="Test Coverage">
    <img src="https://img.shields.io/scrutinizer/quality/g/igorsgm/laravel-redash/main?style=flat-square" alt="Code Quality">
    <a href="https://packagist.org/packages/igorsgm/laravel-redash">
        <img src="https://img.shields.io/packagist/dt/igorsgm/laravel-redash.svg?style=flat-square" alt="Total Downloads">
    </a>
</p>

<hr/>

## ✨ Features

> - **Queries**: Easily create, edit, or archive query objects directly from your Laravel application. The package returns paginated arrays of query objects and supports the extraction of individual query objects too.
> - **Query Results**: Initiate a new query execution or return a cached result effortlessly. Handle parameterized queries and manage max_age for cache bypassing with provided methods.
> - **Dashboards**: Create, edit, or archive dashboard objects seamlessly. Fetch an array of dashboard objects or individual ones directly from your Laravel application.
> - **Jobs**: Monitor the status of your query tasks effectively.

## 1️⃣ Installation

- You can install the package via composer:
```bash
composer require igorsgm/laravel-redash
```

- You can publish the config file with:
```bash
php artisan vendor:publish --tag="laravel-redash-config"
```

## 2️⃣ Usage
#### Define Redash API credentials in your `.env` file. i.e.:

```
REDASH_BASE_URL=foo:bar
REDASH_API_KEY=12345678900987654321
```

### Summary
- Redash API Documentation: https://redash.io/help/user-guide/integrations-and-api/api


| Resource               | Methods                                                                           |
|------------------------|-----------------------------------------------------------------------------------|
| Redash::queries()      | all, get, create, update, delete, getCachedResult, executeOrGetResult, getResult  |
| Redash::queryResults() | get                                                                               |
| Redash::dashboards()   | all, get, create, update, delete                                                  |
| Redash::jobs()         | get                                                                               |

### Queries
```php
// Returns a paginated array of query objects.
Redash::queries()->all();

// Returns an individual query object.
Redash::queries()->get($queryId);

// Create a new query object.
Redash::queries()->create([
    'name' => 'My Query',
    'data_source_id' => 1,
    'query' => 'SELECT * FROM table',
    'description' => 'My Query Description',
    // ...
]);

// Edit an existing query object.
Redash::queries()->update($queryId, [
    'name' => 'My New Query Name',
    // ...
]);

// Archive an existing query.
Redash::queries()->delete($queryId);

// Get a cached result for this query ID
Redash::queries()->getCachedResult($queryId);

// Initiates a new query execution or returns a cached result.
Redash::queries()->executeOrGetResult($queryId, [
    'parameters' => [
        'foo' => 'bar',
    ],
    'max_age' => 0,
]);
```
- Execute a Query and return its result once ready (custom method).
```php
// The maximum age (in milliseconds) of a cached result that the method should return.
// If a cached result is older than this, a new query execution will begin.
// Set to `0` to always start a new execution.
$maxAge = 1800;

// The number of times to retry the query execution if it is still in progress.
$retryAttempts = 20;

Redash::queries()->getResult($queryId, [
    'foo' => 'bar',
], $maxAge, $retryAttempts);
```
### Query Results

```php
// Returns a query result
Redash::queryResults()->get($queryResultId);
```

### Dashboards

```php
Redash::dashboards()->all();
Redash::dashboards()->get($dashboardId);
Redash::dashboards()->create([
    // ...
]);
Redash::dashboards()->update($dashboardId, [
    // ...
]);
Redash::dashboards()->delete($dashboardId);
```

### Jobs

```php
Redash::jobs()->get($jobId);
```

___
### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email igor.sgm@gmail.com instead of using the issue tracker.

## Credits

- [Igor Moraes](https://github.com/igorsgm)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

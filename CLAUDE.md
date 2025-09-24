# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Laravel Redash is a Laravel package that provides a wrapper for the Redash.io APIs, allowing Laravel applications to interact with Redash dashboards, queries, query results, and jobs. The package follows Laravel package conventions and uses Spatie's Laravel Package Tools.

## Architecture

### Core Components

- **Main Class**: `Redash` - The main service class that provides factory methods for resource classes
- **Service Provider**: `RedashServiceProvider` - Registers the package configuration and HTTP macro
- **Resources**: Located in `src/Resources/` - Handle API interactions for different Redash entities:
  - `Queries` - Query CRUD operations and execution
  - `QueryResults` - Fetch query result data
  - `Dashboards` - Dashboard CRUD operations
  - `Jobs` - Monitor query job status
- **Traits**: `HasParams` - Shared parameter handling for resources
- **Enums**: `JobStatus` - Job status constants
- **Facade**: `Redash` - Laravel facade for the main service

### HTTP Client

The package extends Laravel's HTTP client with a `redash()` macro that automatically configures:
- Authentication using API key with "Key" prefix
- Base URL configuration
- Parameter handling

### Configuration

Configuration is handled through `config/redash.php`:
- `api_key` - Redash API key (from `REDASH_API_KEY` env var)
- `base_url` - Redash instance URL (from `REDASH_BASE_URL` env var)

## Development Commands

### Testing
```bash
# Run all tests
composer test
# or
vendor/bin/pest

# Run tests with coverage
composer test-coverage
# or
vendor/bin/pest --coverage
```

### Code Quality
```bash
# Run static analysis
composer analyse
# or
vendor/bin/phpstan analyse

# Format code
composer format
# or
vendor/bin/pint
```

### Package Development
```bash
# Discover packages (runs automatically after composer install/update)
composer post-autoload-dump
```

## Testing Framework

The project uses **Pest PHP** (v2/v3/v4) as the testing framework with:
- Feature tests in `tests/Feature/`
- Uses Orchestra Testbench for Laravel package testing
- PHPUnit XML configuration in `phpunit.xml.dist`
- Coverage reports generated in `build/` directory

## Code Style

The project uses Laravel Pint with:
- Laravel preset
- Custom rules defined in `pint.json`
- Strict types declaration enforced
- Global namespace imports for classes, constants, and functions
- Ordered class elements and interfaces

## Package Structure

```
src/
├── Redash.php              # Main service class
├── RedashServiceProvider.php # Package service provider
├── Facades/
│   └── Redash.php          # Laravel facade
├── Resources/
│   ├── Concerns/
│   │   └── HasParams.php   # Parameter handling trait
│   ├── Dashboards.php      # Dashboard operations
│   ├── Jobs.php            # Job monitoring
│   ├── Queries.php         # Query operations
│   └── QueryResults.php    # Query result retrieval
└── Enums/
    └── JobStatus.php       # Job status constants
```

## API Usage Patterns

All resources follow consistent patterns:
- Use `Http::redash($this->params)` for authenticated requests
- Support parameter injection via `setParams()` method
- Return JSON responses directly from Redash API
- Handle both paginated collections and individual resources

## Dependencies

Key dependencies:
- Laravel/Illuminate (^10.0|^11.0|^12.0|^13.0)
- GuzzleHTTP (^7.0)
- Spatie Laravel Package Tools (^1.92.7)
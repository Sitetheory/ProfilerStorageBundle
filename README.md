# ProfilerStorageBundle [![License](https://poser.pugx.org/sitetheory/profiler-storage-bundle/license)](https://packagist.org/packages/sitetheory/profiler-storage-bundle)
Sitetheory Profiler Storage Bundle for Symfony 2.8+

## Package Information
[![Latest Stable Version](https://poser.pugx.org/sitetheory/profiler-storage-bundle/version)](https://packagist.org/packages/sitetheory/profiler-storage-bundle)
[![Latest Unstable Version](https://poser.pugx.org/sitetheory/profiler-storage-bundle/v/unstable)](//packagist.org/packages/sitetheory/profiler-storage-bundle)
[![Total Downloads](https://poser.pugx.org/sitetheory/profiler-storage-bundle/downloads)](https://packagist.org/packages/sitetheory/profiler-storage-bundle)

## Code Inspection

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Sitetheory/ProfilerStorageBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Sitetheory/ProfilerStorageBundle/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/Sitetheory/ProfilerStorageBundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Sitetheory/ProfilerStorageBundle/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/Sitetheory/ProfilerStorageBundle/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Sitetheory/ProfilerStorageBundle/build-status/master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/Sitetheory/ProfilerStorageBundle/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)

# Overview
We'll be maintaining the following Profiler Bindings for the foreseeable future:

- MemCache
- MongoDB
- MySQL
- PDO
- Redis
- SQLite

# Installation

## Composer
Add this bundle to your Composer Package and follow the configuration below.

```bash
composer require sitetheory/profiler-storage-bundle
```

If you are using MongoDB, you will also need to add the MongoDB Driver.  Instructions are available in the official repository: [github.com/mongodb/mongo-php-library](https://github.com/mongodb/mongo-php-library).

## Configuration
Enable the Bundle:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new Sitetheory\Bundle\ProfilerStorageBundle\SitetheoryProfilerStorageBundle()
        );

        // ...
    }

    // ...
}
```

Add the following to your `config.yml` to control the location for Profiler Storage:

### Mongo

```yaml
sitetheory_profiler_storage:
    profiler:
        defaultStorage: false
        class: Sitetheory\Bundle\ProfilerStorageBundle\Profiler\MongoDbProfilerStorage
        dsn: "mongodb://%user%:%password%@%host%:%port%/%name%/profiler"
        ttl: 3600
```

### MySQL

```yaml
sitetheory_profiler_storage:
    profiler:
        defaultStorage: false
        class: Sitetheory\Bundle\ProfilerStorageBundle\Profiler\MysqlProfilerStorage
        dsn: "mysql:host=%host%;port=%port%;dbname=%name%"
        username: "%user%"
        password: "%password%"
        ttl: 3600
```

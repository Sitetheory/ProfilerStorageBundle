# ProfilerStorageBundle
Sitetheory Profiler Storage Bundle for Symfony 2.8+

[![Latest Stable Version](https://poser.pugx.org/sitetheory/profiler-storage-bundle/version)](https://packagist.org/packages/sitetheory/profiler-storage-bundle)
[![Total Downloads](https://poser.pugx.org/sitetheory/profiler-storage-bundle/downloads)](https://packagist.org/packages/sitetheory/profiler-storage-bundle)
[![Latest Unstable Version](https://poser.pugx.org/sitetheory/profiler-storage-bundle/v/unstable)](//packagist.org/packages/sitetheory/profiler-storage-bundle)
[![License](https://poser.pugx.org/sitetheory/profiler-storage-bundle/license)](https://packagist.org/packages/sitetheory/profiler-storage-bundle)

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

## Configuration
Add the following to your `config.yml` to control the location for Profiler Storage:

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
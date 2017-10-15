# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Changed
- Minimum Symfony's components version bumped to `~2.7`
- `PabloK\SupercacheBundle\Filesystem\Finder` will
throw `PabloK\SupercacheBundle\Exceptions\EmptyPathException`
when you pass empty `$cacheDir`
- Removed `beberlei/assert` dependency
- Added `PabloK\SupercacheBundle\Cache\CacheType` class for type constants
- Added `PabloK\SupercacheBundle\Factory\ResponseFactory` class for creating new cache responses
from `PabloK\SupercacheBundle\Cache\CacheElement`
- Bumped `PHPUnit` to `^6.1` 
- Added `symfony/yaml` as a dev dependency
- Allow `^3.0` version of `symfony/phpunit-bridge`

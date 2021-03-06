# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.6.0] - 2018-12-09

### Fixed

- [[#4]] Fix Symfony's 4.2 deprecations 

## [0.5.0] - 2018-03-30

### Added
- Added `churn-php`
- CodeClimate integration
- `PHPMessDetector` to CI
- `PHP-Paraller-linter` to CI 

### Changed
- In CI install `vendor-bin` dependencies only when they are needed
- Disabled `XDebug` by default in CI
- Only active `symfony` versions will be supported - added `2.7`, but dropped `3.0` and `3.1`
- Minimum `PHP` is `7.1.3`
- Moved handling request logic to dedicated class `PabloK\SupercacheBundle\Request\Handler`
- Request/Response Handlers marked as `@internal`
- Renamed `\PabloK\SupercacheBundle\Factory\ResponseFactory` to `\PabloK\SupercacheBundle\Response\ResponseFactory`
  this can be a BC if you use class 
- `\PabloK\SupercacheBundle\Response\ResponseFactory` marked as `final` and `@internal` 

### Removed
- `KernelListener` class - if you use this class then it is a BC break, but that class was meant for internal use only

## [0.4.0] - 2018-01-06
### Added
- Added `symfony/debug` dependency, as "suggest" section says, "for tracking deprecated interfaces usages at runtime with DebugClassLoader"
- Added sorting `composer.json` dependencies
- Added `dist` as a preferred install method 
- Added `Symfony\Bridge\PhpUnit\SymfonyTestsListener` to `phpunit.xml.dist` listeners

### Changed
- Bumped `voku/html-min` to `^3.0.1` version to avoid conflicts with `symfony/polyfill`
- Fixed `symfony 4.0.3+` commands' "public alias" change   

## [0.3.2] - 2017-12-25
### Added
- Introduce `PHPStan`
- Added `SHELL_VERBOSITY` env to `phpunit.xml` to hide output from default logger
- Added `supercache:checks` `composer`'s `scripts` to run all checks with one command 

### Changed
- `PHPCSFixer` moved to `vendor-bin`
- `PHPCSFixer` check added to CI
- Allow `fast_finish` on TravisCI build
- Ignore only main `composer.lock` file and allow `vendor-bin` packages to use `composer.lock` file

## [0.3.1] - 2017-12-21
### Added
- Added `voku/simple_html_dom` to dependencies to make sure HtmlDomSimple class have `fixhtmloutput` method

## [0.3.0] - 2017-12-21
### Added
- Added badges with build status to `README.md` file
- Added support for `Symfony` `4.0` components

### Changed
- Bumped Vagrant's Composer and PHPCSFixer
- Bumped minimum `PHP` version to `7.1.3`
- Bumped minimum `Symfony` components version to `2.8`
- Reverted `PHPUnit` to `5.7` version
- Bumped `voku/html-min` to `2.0` version

## [0.2.0] - 2017-11-21
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
- Use `PabloK\SupercacheBundle\Factory\ResponseFactory` in `\PabloK\SupercacheBundle\Cache\RequestHandler`
- Introduce PHP-CS-Fixer
- Added `voku/html-min` to minify html content
- Added `--prefer-dist` to Composer to TravisCI builds.
- Changed Vagrant PHP OPcache folder to `/home/vagrant/tmp/php/opcache`
- Cache Composer files in TravisCI builds
- Added `.php_cs.dist` to files ignored on export

[#4]: https://github.com/PabloKowalczyk/SupercacheBundle/pull/4

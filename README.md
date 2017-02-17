HashedAssetBundle
=================

The HashedAssetBundle provides an asset version strategy which uses a hash
of the file content as asset version. This allows bumping the asset version
separately for each asset (automatically).

[![Build Status](https://travis-ci.org/Incenteev/hashed-asset-bundle.svg?branch=master)](https://travis-ci.org/Incenteev/hashed-asset-bundle) [![Total Downloads](https://poser.pugx.org/incenteev/hashed-asset-bundle/downloads.svg)](https://packagist.org/packages/incenteev/hashed-asset-bundle) [![Latest Stable Version](https://poser.pugx.org/incenteev/hashed-asset-bundle/v/stable.svg)](https://packagist.org/packages/incenteev/hashed-asset-bundle)

## Installation

Use [Composer](https://getcomposer.org) to install the bundle:

```bash
$ composer require incenteev/hashed-asset-bundle
```

## Usage

Register the bundle in the kernel:

```php
// app/AppKernel.php

// ...

class AppKernel extends Kernel {
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Incenteev\HashedAssetBundle\IncenteevHashedAssetBundle(),
        );
    }
}
```

Then configure FrameworkBundle to use the new version strategy:

```yaml
framework:
    assets:
        version_strategy: incenteev_hashed_asset.strategy
```

If you are using Symfony <3.1, this configuration setting is not available.
Here is the workaround:

```yaml
# app/config/config.yml
framework:
    assets:
        version: dummy # set a dummy version so that the package does not use the empty version
        
services:
    assets._version__default:
        alias: incenteev_hashed_asset.strategy
# If you use additional packages, you may need to create additional aliases for other packages than `_default`
```

## Advanced configuration

The default configuration should fit common needs, but the bundle exposes
a few configuration settings in case you need them:

```yaml
incenteev_hashed_asset:
    # Absolute path to the folder in which assets can be found
    # Note: in case you apply a base_path in your asset package, it is not
    # yet applied to the string received by the bundle
    web_root: '%kernel.root_dir%/../web'
    # Format used to apply the version. This is equivalent to the
    # `framework > assets > version_format` of the static version strategy
    # of FrameworkBundle.
    version_format: '%%s.%%s'
```

## License

This bundle is under the [MIT license](LICENSE).

## Alternative projects

If you want to apply cache busting by renaming files in your asset pipeline
(for instance with the `gulp-rev` plugin), have a look at
https://github.com/irozgar/gulp-rev-versions-bundle which processes a
manifest file.

## Reporting an issue or a feature request

Issues and feature requests are tracked in the [Github issue tracker](https://github.com/Incenteev/hashed-asset-bundle/issues).

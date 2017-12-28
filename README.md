# AsbestosPHP

A small framework for creating web applications in PHP.

## Install

Basic AsbestosPHP project structure:

    composer.json
    vendor/
    www/
      index.php

Add `goncalomb/asbestos` as a composer dependency:

    "require": {
        "goncalomb/asbestos": "dev-master#<asbestos-commit-hash>"
    }

Optionally add the scripts to copy the asbestos files to the `www/` directory:

    "scripts": {
        "post-install-cmd": [
            "Asbestos\\AsbestosInstaller::copyAsbestosToWWW"
        ],
        "post-update-cmd": [
            "Asbestos\\AsbestosInstaller::copyAsbestosToWWW"
        ]
    }

In this case, use `require './asbestos/core.php';` instead of the normal `require '../vendor/autoload.php';`.

See [www/](www/) for a working example.

## License

AsbestosPHP is released under the terms of the GNU General Public License version 3, or (at your option) any later version. See [LICENSE.txt](LICENSE.txt) for details.

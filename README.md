Let your users create dynamic forms themselve with the form builder bundle

## Installation

Register the bundle in `AppKernel.php`:

```php
<?php

    public function registerBundles()
    {
        return array(
            // ..
            new Balloon\Bundle\FormBuilderBundle\BalloonFormBuilderBundle(),
            // ..
        );
    }
```

Add this section to your `app/config/config.yml` file:

```yaml
balloon_form_builder:
    fields:
        field:
            - label
            - required
            - max_length
        choice:
            - label
            - multiple
            - expanded
            - choices
```

Init assets:

```bash
$ ./app/console assets:install web --symlink
```

Your done now go to `/app_dev.php/form`

## Contributors

[gordonslondon](http://github.com/gordonslondon)

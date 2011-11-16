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
            label:      ~
            required:   false
            max_length: ~
        choice:
            label:      ~
            multiple:   false
            expanded:   false
            choices:    {}
        country:
            label:      ~
        language:
            label:      ~
        timezone:
            label:      ~
        datetime:
            label:      ~
        date:
            label:      ~
        time:
            label:      ~
        checkbox:
            label:      ~
```

Init assets:

```bash
$ ./app/console assets:install web --symlink
```

Your done now go to `/app_dev.php/form`

## Contributors

[gordonslondon](http://github.com/gordonslondon)

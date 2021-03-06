### /!\This bundle has not been updated to Symfony 2.1-RC2+ and is no longer maintained by Balloon/!\


Let your users create dynamic forms and store them in the database.

Features:

- Create forms
- Add fields (register custom fields)
- Answer to a form
- View form results

## Requirements :
 For Symfony 2.1-RC1, use 1.1 tag  
 For a Symfony 2.0 compliant version, use 1.0 tag
 
## Installation (for 2.0 and 2.1-RC1 use, without composer)

Clone the project:

```bash
$ git submodule add -f git://github.com/Balloon/FormBuilderBundle.git vendor/bundles/Balloon/Bundle/FormBuilderBundle
```

Register the bundle in `app/AppKernel.php`:

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

Register the namespace in your `app/autoload.php`:

```php
<?php

$loader->registerNamespaces(array(
    // ...
    'Balloon\\Bundle\\FormBuilderBundle' => __DIR__.'/../vendor/bundles',
    // ...
));
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

Add this section to your `app/config/routing.yml` file:

```yaml
balloon_form_builder:
    resource: "@BalloonFormBuilderBundle/Resources/config/routing.yml"
    prefix:   /form
```

Init assets:

```bash
$ ./app/console assets:install web --symlink
```

And if you haven't a dedicated virtual host, add this to `app/config/config.yml`:

```yaml
framwork:
    templating:
        assets_base_urls: "/balloon-form"
```

Your done now go to `/app_dev.php/form`

## Screenshots

![list form](https://github.com/Balloon/FormBuilderBundle/raw/master/Resources/doc/list.png)
<p>List forms</p>


![edit form](https://github.com/Balloon/FormBuilderBundle/raw/master/Resources/doc/edit.png)
<p>Edit a form</p>

![answer form](https://github.com/Balloon/FormBuilderBundle/raw/master/Resources/doc/answer.png)
<p>Answer to a form</p>

## Contributors

creator [gordonslondon](http://github.com/gordonslondon)
maintener [guillaumepotier]

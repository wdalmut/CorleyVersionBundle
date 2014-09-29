# CorleyVersionBundle

 * Master: [![Build Status](https://travis-ci.org/wdalmut/CorleyVersionBundle.svg?branch=master)](https://travis-ci.org/wdalmut/CorleyVersionBundle)
 * Develop: [![Build Status](https://travis-ci.org/wdalmut/CorleyVersionBundle.svg?branch=develop)](https://travis-ci.org/wdalmut/CorleyVersionBundle)

Just an unified way in order to bump app version for Symfony2 application

In your `config/config.yml` add an import
```yml
imports:
    - { resource: version.yml }
```

If you want to print it in your templates, just add the version in your twig
configuration


```yaml
# config/config.yml
twig:
    globals:
        version: %version%
```

Now you can use it in your templates

```jinja
<footer>
    Version: {{ version.number }}
</footer>
```

You can also append the version number after your static resources

```jinja
{% javascripts
    '@CorleyBaseBundle/Resources/public/js/jquery.min.js'
    '@CorleyBaseBundle/Resources/public/bootstrap/js/bootstrap.min.js'
    '@CorleyBaseBundle/Resources/public/select2/select2.min.js'
    '@CorleyBaseBundle/Resources/public/js/bootstrap-datepicker.js'
    '@CorleyBaseBundle/Resources/public/js/theme.js' filter='uglifyjs' output='js/compiled/base.js' %}
    <script type="text/javascript" src="{{ asset_url }}?v={{ version.number }}"></script>
{% endjavascripts %}
```

Or you can use it in your git flow release process

```shell
$ git flow release start 1.0.0
$ app/console corley:version 1.0.0
$ git commit -a -m "Bumped version 1.0.0
$ git flow release finish 1.0.0
```


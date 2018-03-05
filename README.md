TwigExceptionBundle
=============


## About

Use to make customizables Twig exception pages for routes or specific HTTP status codes.

## Features

* Change default error templates from routes and status codes
* Enable or disable custom error templates for debug environnement

## Configuration

If you need to add custom route handlers, disable the bundle for some environnements or activate custom template for debug mode, you can do it through the configuration.

    kwrz_twig_exception:
      enabled: true
      with_debug: false
      handlers:
        - { regex: ^\/admin, template: "MyBundle:Admin:error404.html.twig", status_code: [404] }
        - { regex: ^\/admin, template: "MyBundle:Admin:errorDefault.html.twig" }
        - { host: ^member\.domain\.tld, regex: ^\/, template: "MyBundle:Member:errorDefault.html.twig" }
        - { regex: ^\/, template: "MyBundle:Front:errorDefault.html.twig" }

Pay attention to the order in which your handlers are declared. There are processed in the same order as in the configuration. If you specify the status_code array in an handler row, the error template will be used only if the expected response status code is in this array.

By default, the `kwrz_twig_exception.with_debug` option is set to false. If you want to view custom error pages in `kernel.debug` mode, simply set this option to true.

If you want to disable this bundle in some environment, simply set the `kwrz_twig_exception.enabled` option to false.

## Installation (Symfony 3.4+)

Require the `modnarlluf/twig-exception` package in your composer.json and update your dependencies.

    $ composer require "modnarlluf/twig-exception"

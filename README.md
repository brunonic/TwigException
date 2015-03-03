TwigExceptionBundle
=============


[![SensionLab Insight](https://insight.sensiolabs.com/projects/17d6cbfb-07cc-491a-bb0e-5982fbaf9dc7/big.png)](https://insight.sensiolabs.com/projects/17d6cbfb-07cc-491a-bb0e-5982fbaf9dc7)

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
        - { regex: ^\/admin, template: "MyBundle:Admin:error404.html.twig", status_code: [404]}
        - { regex: ^\/admin, template: "MyBundle:Admin:errorDefault.html.twig"}
        - { regex: ^\/, template: "MyBundle:Front:errorDefault.html.twig"}

Pay attention to the order in which your handlers are declared. There are processed in the same order as in the configuration. If you specify the status_code array in an handler row, the error template will be used only if the expected response status code is in this array.

By default, the `kwrz_twig_exception.with_debug` option is set to false. If you want to view custom error pages in `kernel.debug` mode, simply set this option to true.

If you want to disable this bundle in some environment, simply set the `kwrz_twig_exception.enabled` option to false.

## Installation (Symfony 2.4+)

Require the `kwrz/twig-exception` package in your composer.json and update your dependencies.

    $ composer require "kwrz/twig-exception"

Add the KwrzTwigExceptionBundle to your application's kernel:

    public function registerBundles()
    {
        $bundles = array(
            ...
            new Kwrz\Bundle\TwigExceptionBundle\KwrzTwigExceptionBundle(),
            ...
        );
        ...
    }

## Licence

The MIT License (MIT)

Copyright (c) 2014 Kwrz

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

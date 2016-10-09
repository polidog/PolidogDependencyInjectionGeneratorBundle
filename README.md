# Polidog/DependencyInjectionGeneratorBundle

このBundleは、`generate:bundle`した時に「Are you planning on sharing this bundle across multiple applications?」を「no」と入力してしまったけど、あとでやっぱ「yes」にすればよかったと後悔したいと向けに作ったBundleです。
DependencyInjectionディレクトリの`Configuration.php`と`Extension.php`を生成するためのバンドルです。


## Installation

```
$ composer require polidog/dependency-injection-generator-bundle
```

```
// app/AppKernel.php

    public function registerBundles()
    {
        $bundles = [
            // ...
            new Polidog\DependencyInjectionGeneratorBundle\PolidogDependencyInjectionGeneratorBundle(),
        ];
        // ...


    }

```

## Usage

```
$ app/console generate:bundle:dependency-inject --namespace=Polidog/HogeBundle --format=yaml
```


```
$ ls src/Polidog/HogeBundle

drwxr-xr-x  7 polidog  staff   238B  9 24 21:43 Controller
drwxr-xr-x  4 polidog  staff   136B 10  9 16:50 DependencyInjection << generated directory
drwxr-xr-x  3 polidog  staff   102B  9 24 21:43 Form
drwxr-xr-x  4 polidog  staff   136B  9 16 22:29 Resources
drwxr-xr-x  5 polidog  staff   170B  9 24 21:43 Search
-rw-r--r--  1 polidog  staff   161B  9 16 22:29 PolidogHogeBundle.php
```
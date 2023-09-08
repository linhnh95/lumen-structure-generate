## lumen-generate

Laravel Lumen Generate is a package that helps to create structure and help with the project construction process.

## Installation

Require this package with composer. It is recommended to only require the package.

```shell
composer require linhnh95/laravel-api-lumen-generate
```

## Usage

After running the composer. Turn on the terminal screen and run the command


Adding the text after `{` of `register` function
```
/**BEGIN CONFIG**/
/**END CONFIG**/
```


```shell
php artisan lumen-generate:init
```

To create a series of processing files for a Model using 5 layers

```shell
php artisan lumen-generate:make {name} // name can be in the form Folder/FileName
```
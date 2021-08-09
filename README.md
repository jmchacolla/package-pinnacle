# Processmaker Package Pinnacle
This package provides the necessary base code to start the developing a package in ProcessMaker 4.

## Development
If you need to create a new ProcessMaker package run the following commands:

```
git clone https://github.com/ProcessMaker/package-pinnacle.git
cd package-pinnacle
php rename-project.php package-pinnacle
composer install
npm install
npm run dev
```

## Installation
* Use `composer require processmaker/package-pinnacle` to install the package.
* Use `php artisan package-pinnacle:install` to install generate the dependencies.

## Navigation and testing
* Navigate to administration tab in your ProcessMaker 4
* Select `Skeleton Package` from the administrative sidebar

## Uninstall
* Use `php artisan package-pinnacle:uninstall` to uninstall the package
* Use `composer remove processmaker/package-pinnacle` to remove the package completely

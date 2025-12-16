Creating a new package in Laravel involves several steps, including setting up the package structure, configuring Composer, and defining its functionality.
1. Create the Package Directory and Structure:
Inside your Laravel project's root, create a packages directory (if it doesn't exist).
Inside packages, create a directory for your vendor name (e.g., your-vendor).
Inside your vendor directory, create a directory for your package name (e.g., your-package).
Inside your-package, create a src directory to hold your package's source code. You might also create directories like config, resources/views, routes, database/migrations, etc., depending on your package's needs. 
2. Initialize Composer:
Navigate into your package's root directory: cd packages/your-vendor/your-package.

# Run: composer init     (lưu ý là cần chạy init để tạo file composer.json và các source khác chứ không được tạo tile composer.json thủ công sẽ không load package vào được root composer.json)

	to create a composer.json file for your package. Follow the prompts to provide details like package name, description, author, and license.

3. Define the Package in composer.json:
Ensure your package's composer.json includes the necessary information, especially the autoload section to define how your classes are loaded. For example:
Mã

    {
        "name": "your-vendor/your-package",
        "description": "A brief description of your package.",
        "type": "laravel-package",
        "license": "MIT",
        "autoload": {
            "psr-4": {
                "YourVendor\\YourPackage\\": "src/"
            }
        },
        "minimum-stability": "dev",
        "prefer-stable": true
    }
4. Register the Package in Your Laravel Application:
In your main Laravel project's composer.json file, add a repositories entry to point to your local package:
Mã

    {
        "repositories": [
            {
                "type": "path",
                "url": "packages/your-vendor/your-package"
            }
        ],
        "require": {
            "your-vendor/your-package": "@dev"
        }
    }

# Run: composer update (in your main Laravel project to link the package.)

5. Create a Service Provider:
Inside your package's src directory, create a service provider file (e.g., YourPackageServiceProvider.php). This class will extend Illuminate\Support\ServiceProvider.
Implement the register() and boot() methods to register services, publish configuration files, load views, define routes, etc.
Mã

    <?php

    namespace YourVendor\YourPackage;

    use Illuminate\Support\ServiceProvider;

    class YourPackageServiceProvider extends ServiceProvider
    {
        public function register()
        {
            // Register bindings, singletons, etc.
            $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'your-package');
        }

        public function boot()
        {
            // Load views, routes, migrations, publish assets, etc.
            $this->loadViewsFrom(__DIR__.'/../resources/views', 'your-package');
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('your-package.php'),
            ], 'your-package-config');
        }
    }
6. Register the Service Provider:
In your main Laravel project's config/app.php, add your package's service provider to the providers array:
Mã

    'providers' => [
        // ...
        YourVendor\YourPackage\YourPackageServiceProvider::class,
    ],
7. Add Functionality (Routes, Controllers, Views, etc.):
Create routes in routes/web.php (or api.php) within your package.
Develop controllers in src/Controllers.
Design views in resources/views.
Create configuration files in config.
8. Test Your Package:
Develop unit and feature tests for your package's functionality.
This outlines the essential steps for creating a new Laravel package. Remember to adapt the structure and functionality to your specific package requirements.
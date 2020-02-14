# PatchMigrator [php] [lumen]

As Laravel migrations work, this package allows you to keep track of code migrations and execute code blocks as if they are migrations.

## Installation
This package needs Laravel/Lumen 5.x or latest

Installing this package through Composer. Require it directly from the Terminal to take the last stable version:
```bash
$ composer require alex90badea/patch-migration 
```

Add Service Provider in `boostrap/app`
```
$app->register(AlexBadea\PatchMigration\PatchMigrationServiceProvider::class);
```

First use only:
```
php artisan patch:install
```

Available commands:

| Tables        | Are         |
| ------------- |-------------|
| php artisan patch:install | Creates the table required for this package to work.|
| php artisan patch:make {name} | Create a new patch file. (name : The name of the patch)|
| php artisan patch:status | List all patches status.|
| php artisan patch:run | Run the patches.|

Basic example of a patch that you want to run when you deploy the app. Let's create a functionality to change all user's email addresses to lowercase. You will run `php artisan patch:make change_users_emails_to_lowercase`. The following file will be created under `/patches` folder of your root application. 

```php

<?php

class ChangeUsersEmailsToLowercase
{
    /**
     * Patch handle.
     *
     * @return void
     */
    public function handle()
    {
        // here you will write the code
    }

}


```

At the end you will run `php artisan patch:run` and the handle method will be executed.


## License
This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
    

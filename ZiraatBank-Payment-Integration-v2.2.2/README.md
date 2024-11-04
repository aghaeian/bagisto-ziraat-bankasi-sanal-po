### 1. Introduction:

Laravel ZiraatBank payment – An easy way for online payment. Today we are going to introduce ZiraatBank payment for Laravel.

ZiraatBank make it easy for customers to pay by accepting the payments they prefer, including major credit cards, signature debit cards.

* Enable/disable payment method from admin panel.
* Provide payment directly to the admin account.
* Accept all the cards that ZiraatBank supports.

### 2. Requirements:

* **Bagisto**: v2.2.2

### 3. Installation:

* Unzip the respective extension zip and then merge "packages" folder into project root directory.

* Goto composer.json file and add following line under psr-4

~~~
"Webkul\\ZiraatBank\\": "packages/Webkul/ZiraatBank/src"
~~~

* Goto config/app.php file and add following line under 'providers'

~~~
Webkul\ZiraatBank\Providers\ZiraatBankServiceProvider::class
~~~

* Run these commands below to complete the setup

~~~
composer dump-autoload
~~~
~~~
composer require ziraat_bank/ziraat_bank_php
~~~
~~~
  php artisan route:cache
~~~

> That's it, now just execute the project on your specified domain.

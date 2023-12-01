# php-ci4-basic-jwt-auth

```text
- create project
composer create-project codeigniter4/appstarter php-ci4-basic-jwt-auth

- run app
php spark serve

-create model
php spark make:model UserModel

-create controller
php spark make:controller UserApiController

- use jwt
composer require firebase/php-jwt

-make filter Auth
php spark make:filter Auth
```
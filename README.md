# P7BileMo
Seventh project in my PHP / Symfony developper course with OpenClassrooms

## Installation
Clone the repository.

In the terminal cd to project root directory.

Use the package manager [composer](https://getcomposer.org/download/) to install the projects PHP dependencies.

```bash
composer install
```

Create a .env file the following is an example of a full .env file:

```bash
# In all environments, the following files are loaded if they exist,
# the latter taking precedence over the former:
#
#  * .env                contains default values for the environment variables needed by the app
#  * .env.local          uncommitted file with local overrides
#  * .env.$APP_ENV       committed environment-specific defaults
#  * .env.$APP_ENV.local uncommitted environment-specific overrides
#
# Real environment variables win over .env files.
#
# DO NOT DEFINE PRODUCTION SECRETS IN THIS FILE NOR IN ANY OTHER COMMITTED FILES.
#
# Run "composer dump-env prod" to compile .env files for production use (requires symfony/flex >=1.2).
# https://symfony.com/doc/current/best_practices.html#use-environment-variables-for-infrastructure-configuration

###> symfony/framework-bundle ###
APP_ENV=dev
APP_SECRET=SECRET
###< symfony/framework-bundle ###

###> doctrine/doctrine-bundle ###
# Format described at https://www.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#connecting-using-a-url
# IMPORTANT: You MUST configure your server version, either here or in config/packages/doctrine.yaml
#
# DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
# DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7"
DATABASE_URL="mysql://root:@127.0.0.1:3306/p7_bile_mo?serverVersion=mariadb-10.4.10"
###< doctrine/doctrine-bundle ###
###> lexik/jwt-authentication-bundle ###
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=PASSPHRASE
###< lexik/jwt-authentication-bundle ###
```

##Generate the SSL keys:
`php bin/console lexik:jwt:generate-keypair`

Your keys will land in config/jwt/private.pem and config/jwt/public.pem (unless you configured a different path).

##Setup
Use doctrine to migrate database :

`php bin/console doctrine:migrations:migrate`

and load data fixtures :

`php bin/console doctrine:fixtures:load`

## Start project

From terminal `symfony server:start`

## Structure

1. bin
2. config
3. migrations
4. postman   
5. public
6. src
7. templates
8. var
9. vendor

##Postman collection
A postman collection has been provided in /postman, import the collection.

Use the 'Get JWT' endpoint to generate a JWT token to use in the 'Authorization' header for each endpoint.

## License
[MIT](https://choosealicense.com/licenses/mit/)

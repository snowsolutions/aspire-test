

# Mini Aspire API



## System requirements

Webserver Apache or Nginx or simply use built-in PHP server (artisan serve)
- PHP 8.1+
- MySQL 5.7+

## Installation Step
Assume that you already have PHP and MySQL installation on your environment
1. Create 2 empty databases `aspire` and `aspire_test`
2. Adjust environment files `.env` and `.env.testing` to suit your configuration. You can copy the content of `.env.example` for these 2 files
3. Run migration for local environment with command
```
php artisan config:cache --env="local" && php artisan migrate:fresh --seed --env="local"
```
4. Start browsing the site with virtual host setup or with `php artisan serve`

## Configuration
- use `.env` file for local environments and `.env.testing` file for test environments

## Account & Test data
### User account
This account credential is used to create new loan application & making payment
```
# User 1
user@test.com / 123456
```

```
# User 2
victoria@test.com / 123456
```
### Admin account
This account credential is used to approve loan application
```
admin@admin.com / 654321
```
### Loan Application data
After run the migration command with `--seed` the database will have 3 record of loan application:
- `ID 1` : belongs to user 1 and `PENDING` status
- `ID 2` : belongs to user 1 and `APPROVED` status
- `ID 3` : belongs to user 2 and `PENDING` status

## APIs
### Postman collection
You can use the provided Postman Collection to import into your Postman app
```
./docs/postman/aspire-mini-api.postman_collection.json
```
Before you try out, take a minute to configure the `host` value in `variables` setting of the collection to fit your environment

## Staging application
There is a staging application hosted on cloud and you can use `Postman` to access directly to the application with below endpoint URL

```
# User API endpoint
https://aspire.phucnguyen68.com/api/
```

```
# Admin API endpoint
https://aspire.phucnguyen68.com/api/admin/
```

## Tests
**Unit test command**
You can test the application by run the following command
```
composer test
```
This command included 3 single command as below, you can run individual as your need
```
"@php artisan config:cache --env=testing"
"@php artisan test --env=testing"
"@php artisan config:cache --env=local"
```

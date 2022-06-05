# Employbrand Apply PHP SDK

Connect your app to the Employbrand Apply application with the easy-to-use PHP SDK. When instantiating the EmploybrandApplyClient class, you must provide the company ID and company access token. You can get these credentials form in the Employbrand Hub under 'Advanced > Employbrand API > Apply'.  

## Installation
```bash
composer require employbrand/apply-php-sdk
```

## Usage
Creating the client.
```php
$client = new EmploybrandApplyClient($companyId, $accessToken);
$client->...
```

# PHP CNPJ Validator

Brazilian CNPJ validator and verification digit (DV) calculator for PHP.

## Installation

```bash
composer require souzajluiz/CNPJ_2_0
```

## Usage

```php
use Souzajluiz\CNPJ\CNPJ;

$isValid = CNPJ::isValid('45.723.174/0001-10');

$dv = CNPJ::calculateDV('457231740001');
```

## Running tests

```bash
composer install
composer test
```

## License

MIT

# Laravel support resources

<p align="center">
<a href="LICENSE"><img src="https://img.shields.io/badge/license-MIT-lightgrey.svg" alt="License"></a>
<a href="https://packagist.org/packages/attla/support"><img src="https://img.shields.io/packagist/v/attla/support" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/attla/support"><img src="https://img.shields.io/packagist/dt/attla/support" alt="Total Downloads"></a>
</p>

🛠️ Collection of resources to improve and extend ways to use laravel.

## Installation

```bash
composer require attla/support
```

## Usage

Arr examples:

```php

use Attla\Support\Arr as AttlaArr;
use Illuminate\Support\Arr;

// Convert a value to array
AttlaArr::toArray($value); // array
Arr::toArray($value); // array

// Randomize positions of an array
AttlaArr::randomized($value); // array
Arr::randomized($value); // array

```

Str examples:

```php

use Attla\Support\Str as AttlaStr;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

AttlaStr::isBase64($value); // bool
Str::isBase64($value); // bool
(new Stringable($value))->isBase64($value); // bool

AttlaStr::isHttpQuery($value); // bool
Str::isHttpQuery($value); // bool
(new Stringable($value))->isHttpQuery($value); // bool

AttlaStr::isSerialized($value); // bool
Str::isSerialized($value); // bool
(new Stringable($value))->isSerialized(); // bool

```

## License

This package is licensed under the [MIT license](LICENSE) © [Octha](https://octha.com).

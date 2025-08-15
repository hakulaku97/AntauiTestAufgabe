<?php
namespace Test\Database\Csv;

use Test\Database\Query;
use Test\Database\Traits\Cols;
use Test\Database\Traits\From;
use Test\Database\Traits\Where;

class Select extends Query {
    use Cols;
    use Where;
    use From;
}
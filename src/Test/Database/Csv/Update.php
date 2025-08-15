<?php
namespace Test\Database\Csv;

use Test\Database\Query;
use Test\Database\Traits\Set;
use Test\Database\Traits\Table;
use Test\Database\Traits\Where;

class Update extends Query {
    use Set;
    use Table;
    use Where;
}
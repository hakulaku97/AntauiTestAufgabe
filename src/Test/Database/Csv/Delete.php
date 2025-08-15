<?php
namespace Test\Database\Csv;

use Test\Database\Query;
use Test\Database\Traits\Table;
use Test\Database\Traits\Where;

class Delete extends Query {
    use Table;
    use Where;
}
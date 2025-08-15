<?php
namespace Test\Database\Csv;

use Test\Database\Query;
use Test\Database\Traits\Set;
use Test\Database\Traits\Table;

class Insert extends Query {
    use Table;
    use Set;
}
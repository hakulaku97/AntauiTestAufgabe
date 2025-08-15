<?php

namespace Test\Database;

interface Driver {
    /** fetcher */
    public function fetchRow(Query $obj_query, array $arr_alias=[]);
    public function fetchCol(Query $obj_query, array $arr_alias=[]);
    public function fetchOne(Query $obj_query, array $arr_alias=[]);
    public function fetchAll(Query $obj_query, array $arr_alias=[]);
    public function fetchAssoc(Query $obj_query, array $arr_alias=[]);
    public function execute(Query $obj_query, array $arr_alias=[]);
    
    /** query builder */
    public function buildSelect();
    public function buildDelete();
    public function buildUpdate();
    public function buildInsert();
}
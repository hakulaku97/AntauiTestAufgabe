<?php 
namespace Test\Database;

use Test\Database\Traits\Quote;
use Test\Database\Csv\Select;
use Test\Database\Csv\Delete;
use Test\Database\Csv\Insert;
use Test\Database\Csv\Update;

class Csv implements Driver {
    use Quote;

    private string $str_folder;

	public function __construct(string $str_database = '')
	{
	    if(empty($str_database)){
	        trigger_error('No database base folder given.', E_USER_ERROR);
	    }
	    
	    if(!file_exists($str_database)){
	        trigger_error('Given database base folder not there.', E_USER_ERROR);
	    }
	    
	    if(!is_dir($str_database)){
	        trigger_error('Given database base folder is not a directory.', E_USER_ERROR);
	    }
	    
	    $this->setFolder($str_database);
	}

    /**
     *    Set the str_folder value
     *    @param string $str_folder
     */
    public function setFolder(string $str_folder): void
    {
        $this->str_folder = $str_folder;
    }

    /**
     *    Returns the str_folder value.
     *    @return string
     */
    public function getFolder(): string
    {
        return $this->str_folder;
    }

    /**
     * Returns a new Select Builder
     *
     * @return Select
     */
	public function buildSelect(): Select
    {
	    return new Select;
	}

    /**
     * Returns a new Delete Builder
     *
     * @return Delete
     */
	public function buildDelete(): Delete
    {
	    return new Delete;
	}

    /**
     * Returns a new Insert Builder
     *
     * @return Insert
     */
	public function buildInsert(): Insert
    {
	    return new Insert;
	}

    /**
     * Returns a new Update Builder
     *
     * @return Update
     */
	public function buildUpdate(): Update
    {
	    return new Update;
	}

    /**
     * Fetches a row
     *
     * @param Query $obj_query
     * @param array $arr_alias
     * @return array|mixed
     */
	public function fetchRow(Query $obj_query, array $arr_alias=[])
	{
	    $arr_data = $this->fetch($obj_query);
	    if(empty($arr_data)) {
            return [];
        }

	    
	    $arr_cols = $obj_query->__cols;
	    $arr_wheres = $obj_query->__where;
	    $arr_return = [];

	    foreach($arr_data as $arr_row){
	        if(!empty($arr_cols)) {
                foreach($arr_cols as $str_col) {
                    $arr_return[$str_col] = $arr_row[$str_col];
                }
            }
            else {
                $arr_return = $arr_row;
            }

            if(empty($arr_wheres)) {
                return $arr_return;
            }

            if($this->validate($arr_row, $arr_wheres, $arr_alias))  {
                return $arr_return;
            }
	    }

	    return [];
	}

    /**
     * Fetches a column
     *
     * @param Query $obj_query
     * @param array $arr_alias
     * @return array
     */
	public function fetchCol(Query $obj_query, array $arr_alias=[]): array
    {
	    $arr_cols = $obj_query->__cols;
	    if(empty($arr_cols)){
	        trigger_error('Expect column to fetch, none given', E_USER_ERROR);
	    }

	    $str_col = end($arr_cols);
	    $arr_data = $this->fetch($obj_query);

	    if($arr_data === false) {
            return [];
        }

	    $arr_wheres = $obj_query->__where;
	    $arr_return = [];

	    foreach($arr_data as $arr_row){
	        if(empty($arr_wheres)) {
                $arr_return[] = $arr_row[$str_col];
            }
            elseif($this->validate($arr_row, $arr_wheres, $arr_alias)) {
                $arr_return[] = $arr_row[$str_col];
            }
        }

        return $arr_return;
	}

    /**
     * Fetch one entry with the specific conditions
     *
     * @param Query $obj_query
     * @param array $arr_alias
     *
     * @return mixed
     */
	public function fetchOne(Query $obj_query, array $arr_alias=[])
	{
	    $arr_cols = $obj_query->__cols;

	    if(empty($arr_cols)){
	        trigger_error('Expect column to fetch, none given', E_USER_ERROR);

	    }

	    $str_col = end($arr_cols);
	    
	    return $this->fetchRow($obj_query)[$str_col];
	}

    /**
     * Fetch all entries with the specific conditions, with optional pagination
     *
     * @param Query $obj_query
     * @param array $arr_alias
     * @param int|null $page Page number, null for no pagination
     * @param int|null $per_page Number of records per page, null for no pagination
     *
     * @return array ['data' => array, 'total_pages' => int]
     */
    public function fetchAll(Query $obj_query, array $arr_alias = [], ?int $page = null, ?int $per_page = null): array
    {
        $arr_return = $this->fetchAssoc($obj_query, $arr_alias);
        $total_records = count($arr_return);
        $total_pages = 0;

        if (is_int($per_page) && $per_page > 0) {
            $total_pages = (int) ceil($total_records / $per_page);
        } elseif ($total_records > 0) {
            $total_pages = 1;
        }

        if (!empty($arr_return)) {
            if (is_int($page) && is_int($per_page) && $page > 0 && $per_page > 0) {
                $start = ($page - 1) * $per_page;
                $arr_return = array_slice($arr_return, $start, $per_page);
            } elseif (($page !== null && $page <= 0) || ($per_page !== null && $per_page <= 0)) {
                trigger_error('Invalid pagination parameters', E_USER_WARNING);
                $arr_return = [];
            }

            foreach ($arr_return as $i => $arr_row) {
                $arr_return[$i] = array_merge($arr_row, array_values($arr_row));
            }
        }

        return [
            'data' => $arr_return,
            'total_pages' => $total_pages
        ];
    }

    /**
     * Fetch with specific conditions
     *
     * @param Query $obj_query
     * @param array $arr_alias
     *
     * @return array
     */
	public function fetchAssoc(Query $obj_query, array $arr_alias=[]): array
    {
	    $arr_data = $this->fetch($obj_query);
	    if(empty($arr_data)) {
            return [];
        }
	    
	    $arr_cols = $obj_query->__cols;
	    $arr_wheres = $obj_query->__where;
	    $arr_return = [];

	    foreach($arr_data as $arr_row) {
	        $arr_returnRow = [];

	        if(!empty($arr_cols)){
                foreach($arr_cols as $str_col) {
                    $arr_returnRow[$str_col] = $arr_row[$str_col];
                }
            }
            else {
                $arr_returnRow = $arr_row;
            }

            if(empty($arr_wheres)) {
                $arr_return[] = $arr_returnRow;
            }
            elseif($this->validate($arr_row, $arr_wheres, $arr_alias)) {
                $arr_return[] = $arr_returnRow;
            }
	    }

	    return $arr_return;
	}

    /**
     * Execute query
     *
     * @param Query $obj_query
     * @param array $arr_alias
     *
     * @return bool
     */
	public function execute(Query $obj_query, array $arr_alias=[]): bool
    {
	    $str_table = $obj_query->__table;
	    
	    $arr_data = $this->fetch($this->buildSelect()->from($str_table));
	    $arr_head = empty($arr_data) ? $this->fetch($this->buildSelect()->from($str_table), true) :
            array_keys($arr_data[0]);

	    if($arr_data === false) {
            return false;
        }
	    
	    $str_csvFile = realpath($this->getFolder().DIRECTORY_SEPARATOR.$str_table.'.csv');
	    $fp = fopen($str_csvFile, 'w');
	    fputcsv($fp, $arr_head);
	    
	    if($obj_query instanceof Update){
	        $arr_wheres = $obj_query->__where;
	        $arr_set = $obj_query->__set;

	        if(array_key_exists('id', $arr_set)) {
                unset($arr_set['id']);
            }

    	    foreach($arr_data as $arr_row){
    	        if($this->validate($arr_row, $arr_wheres, $arr_alias)){
    	            foreach($arr_set as $str_col => $str_val) {
                        if(array_key_exists($str_col, $arr_row)) {
                            $arr_row[$str_col] = (substr($str_val, 0, 1)===':'?$arr_alias[substr($str_val, 1)]:$str_val);
                        }
                    }


    	        }

                fputcsv($fp, $arr_row);
    	    }
        }
	    
	    if($obj_query instanceof Delete){
	        $arr_wheres = $obj_query->__where;
    	    foreach($arr_data as $arr_row){
    	        if(!$this->validate($arr_row, $arr_wheres, $arr_alias)) {
                    fputcsv($fp, $arr_row);
                }
    	    }
        }
	    
	    if($obj_query instanceof Insert){
            $arr_put = [];
	        $arr_set = $obj_query->__set;
	        $id = 0;

    	    foreach($arr_data as $arr_row){
                fputcsv($fp, $arr_row);
                $id = array_key_exists('id', $arr_row)?max($arr_row['id'], $id):$id;
    	    }

    	    if(in_array('id', $arr_head)) {
                $arr_put['id'] = ++$id;
            }

	        if(array_key_exists('id', $arr_set)) {
                unset($arr_set['id']);
            }

	        foreach($arr_head as $str_col) {
                if(array_key_exists($str_col, $arr_set)) {
                    $arr_put[$str_col] = (substr($arr_set[$str_col], 0, 1)  === ':' ?
                        $arr_alias[substr($arr_set[$str_col], 1)]:$arr_set[$str_col]);
                }
                else {
                    $arr_put[$str_col] = $str_col == 'id' ? $arr_put[$str_col] : NULL;
                }
            }

	        fputcsv($fp, $arr_put);
        }
	    fclose($fp);

	    return true;
	}

    /**
     * Fetches entries
     *
     * @param Query $obj_query
     * @param bool $boo_headOnly
     *
     * @return array|false|null
     */
    private function fetch(Query $obj_query, bool $boo_headOnly=false)
    {
        if(!($obj_query instanceof Select)){
            trigger_error('Invalid argument, expected Test\Database\Csv\Select', E_USER_ERROR);
        }

        $str_from = $obj_query->__from;

        if(empty($str_from)){
            trigger_error('Query object missing FROM clause', E_USER_ERROR);
        }

        $str_csvFile = realpath($this->getFolder().DIRECTORY_SEPARATOR.$str_from.'.csv');
        if(!file_exists($str_csvFile)){
            trigger_error(sprintf('Invalid table object "%s"', $str_from), E_USER_ERROR);
        }

        $arr_head = [];
        $arr_data = [];
        $i = 0;

        if(($handle = fopen($str_csvFile, "r")) !== FALSE){
            while(($arr_row = fgetcsv($handle, 1000, ",")) !== FALSE){
                if(!$i++){
                    $arr_head = $arr_row;

                    if($boo_headOnly) {
                        return $arr_head;
                    }
                }
                else {
                    $arr_data[] = array_combine($arr_head, $arr_row);
                }
            }
            fclose($handle);
        }
        return $arr_data;
    }

    /**
     * Validates if syntax is correct
     *
     * @param $arr_row
     * @param array $arr_wheres
     * @param array $arr_alias
     *
     * @return bool
     */
    private function validate($arr_row, array $arr_wheres=[], array $arr_alias=[]): bool
    {
        if(empty($arr_row)) {
            return false;
        }

        $boo_returnResult = true;
        foreach($arr_wheres as $arr_where) {
            foreach($arr_where as $str_andOr => $arr_whereClause) {
                $arr_keys = array_keys($arr_whereClause);
                $str_col = end($arr_keys);
                $str_val = $arr_whereClause[$str_col];
                $boo_result = $arr_row[$str_col] ==
                    (substr($str_val, 0, 1) === ':'? $arr_alias[substr($str_val, 1)] : $str_val);

                switch($str_andOr){
                    case 'and':
                        $boo_returnResult = $boo_returnResult && $boo_result;
                        break;
                    case 'or':
                        $boo_returnResult = $boo_returnResult || $boo_result;
                        break;
                }
            }
        }
        return $boo_returnResult;
    }
}
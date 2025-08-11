<?php
namespace Test\Database\Traits;

trait Where {
    private array $arr_where = [];

    /**
     * add value to arr_where
     * @param string $str_col
     * @param string $str_value
     * @return Where
     */
    public function where(string $str_col, string $str_value): Where
    {
        $this->arr_where[] = ['and' => [$str_col => $str_value]];
        return $this;
    }

    /**
     * add value to arr_where
     * @param string $str_col
     * @param string $str_value
     * @return Where
     */
    public function orWhere(string $str_col, string $str_value): Where
    {
        $this->arr_where[] = ['or' => [$str_col => $str_value]];
        return $this;
    }
    
    
    /**
     *    Returns the arr_where value.
     *    @return array
     */
    protected function getWhere(): array
    {
        return $this->arr_where;
    }
}
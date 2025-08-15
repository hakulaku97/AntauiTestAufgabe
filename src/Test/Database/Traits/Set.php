<?php
namespace Test\Database\Traits;

trait Set {
    private array $arr_set;

    /**
     * Add value to arr_set
     *
     * @param string $str_col
     * @param string $str_value
     *
     * @return Set
     */
    public function set(string $str_col, string $str_value): self
    {
        $this->arr_set[$str_col] = $str_value;

        return $this;
    }
    
    /**
    *    Returns the arr_set value.
     *
    *    @return array
    */
    protected function getSet(): array
    {
        return $this->arr_set;
    }
}
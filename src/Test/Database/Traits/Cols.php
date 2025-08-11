<?php
namespace Test\Database\Traits;

trait Cols {
    private array $arr_cols=[];

    /**
     *    Add value to arr_cols
     * @param string $str_cols
     * @return Cols
     */
    public function col(string $str_cols): Cols
    {
        $this->arr_cols[] = $str_cols;
        return $this;
    }

    /**
     *    Set the arr_cols value
     * @param array $arr_cols
     *
     * @return Cols
     */
    public function cols(array $arr_cols): Cols
    {
        $this->arr_cols = $arr_cols;
        return $this;
    }
    
    /**
     * Get the arr_cols value
     * @return array
     */
    protected function getCols(): array
    {
        return $this->arr_cols;
    }
}
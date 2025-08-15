<?php
namespace Test\Database\Traits;

trait Table {
    private string $str_table;

    /**
     * Set the str_table value
     *
     * @param string $str_table
     *
     * @return Table
     */
    public function table(string $str_table): self
    {
        $this->str_table = $str_table;

        return $this;
    }

    /**
    *    Returns the str_table value.
    *    @return string
    */
    protected function getTable(): string
    {
        return $this->str_table;
    }
}
<?php
namespace Test\Database\Traits;

trait From {
    private string $str_from = '';

    /**
     * Set the str_from value
     * @param string $str_from
     * @return From
     */
    public function from(string $str_from): From
    {
        $this->str_from = $str_from;
        return $this;
    }
    
    /**
     * Get the str_from value
     * @return string
     */
    protected function getFrom(): string
    {
        return $this->str_from;
    }
}
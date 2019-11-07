<?php

namespace App\Coder;

class ParameterCoder
{
    public function encode(int $param): string
    {
        $currentDate = new \DateTime();
        return dechex(($param + 318) * (int)$currentDate->format('Y'));
    }
    
    public function decode(string $param)
    {
        $currentDate = new \DateTime();
        $paramInt = (int)hexdec($param);
        return ($paramInt / (int)$currentDate->format('Y') - 318);
    }
}

<?php
/**
 * Converts numbers by mixing them with Hangul
 * @author jaysiyo <mail.jaysiyo@gmail.com>
 */
class NumberToMixKorean
{
    /**
     * Unit name
     */
    private $units  = array('', '만', '억', '조', '경'); 

    /**
     * Set numeric units
     */
    private $numberUnit = 10000;

    /**
     * Transformation value by unit
     */
    private $unitResult = array();

    public function __construct() {
        $this->is_64bit();
    }

    /**
     * Check if it is a 64-bit environment
     */
    private function is_64bit() {
        if (strlen(decbin(~0)) !== 64) {
            throw new Exception('Available for 64-bit only');
        }
    }

    /**
     * Converts numbers and returns values
     * @param int number
     */
    public function convert($number)
    {
        $convertValue   = 0;
        if ($number > 0) {            
            foreach ($this->units as $key => $unit) {
                $intValue   = (int) pow($this->numberUnit, $key + 1);
                if ($intValue) {
                    $unitValue = floor(($number % pow($this->numberUnit, $key + 1)) / pow($this->numberUnit, $key));
                    if ($unitValue > 0) {
                        $this->unitResult[$key] = $unitValue;
                    }
                }
            }
            if (empty($this->unitResult) == false) {   
                $convertValue   = ''; 
                krsort($this->unitResult);
                $lastkey    = array_keys($this->unitResult)[count($this->unitResult) - 1];
                foreach ($this->unitResult as $key => $value) {
                    $convertValue   .= sprintf(
                        '%s%s', 
                        number_format($value), $this->units[$key]
                    );              
                }                
                $convertValue   = preg_replace(
                    '/(?<=[가-힣])/u', ' ', $convertValue
                );
            }
        }
        return $convertValue;
    }
}
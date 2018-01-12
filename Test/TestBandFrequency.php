<?php

use Jeremyfornarino\Band\BandFrequency;
use Jeremyfornarino\Band\BandFrequencyDB;
use PHPUnit\Framework\TestCase;

require_once __DIR__."/../vendor/autoload.php";

class TestBandFrequency extends TestCase {
    public function testToArray(){
        $bandFrequency = new BandFrequency("bandName", 1,2,3,4,5,6,7);
        $bandArray = [
            "name" => "bandName",
            "startFrequency"=> 1,
            "stopFrequency" => 2,
            "rbw" => 3,
            "points" => 4,
            "frequencyUnit" => 5,
            "rbwUnit" => 6,
            "bandDivider" => 7
        ];
        $bandFrequencyAsArray = $bandFrequency->__toArray();
        $this->assertEquals(count($bandFrequencyAsArray), count($bandArray));
        foreach ($bandArray AS $key => $value){
            $this->assertArrayHasKey($key, $bandFrequencyAsArray);
            $this->assertEquals($value, $bandFrequencyAsArray[$key]);
        }
    }

    public function testEqual(){
        $bandA = new BandFrequency("coucu", 1,2, 3, 4,5,6,7);
        $this->assertFalse($bandA->equal(null));
        $this->assertFalse($bandA->equal([]));
        $this->assertFalse($bandA->equal(json_decode(json_encode(["key" => "value"]))));
        $this->assertTrue($bandA->equal($bandA));


        $bandB = new BandFrequency("coucu", 1,2, 3, 4,5,6,7);
        $this->assertTrue($bandA->equal($bandB));

        $bandC = new BandFrequency("Hahah", 1,2, 3, 4,5,6,7);
        $this->assertFalse($bandC->equal($bandB));
    }

}
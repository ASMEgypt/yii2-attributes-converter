<?php
/**
 * Created by PhpStorm.
 * User: execut
 * Date: 8/5/14
 * Time: 5:05 PM
 */

namespace execut\attributesConverter\converters;


use execut\TestCase;

class FloatToIntTest extends TestCase {
    public function testConvert() {
        $converter = new FloatToInt();
        $this->assertEquals(112, $converter->convert(1.12));
        $this->assertEquals(113, $converter->convert(1.125));
        $this->assertEquals(112, $converter->convert(1.124));
        $converter->decimals = 1;
        $this->assertEquals(112, $converter->convert(11.2));
    }
} 
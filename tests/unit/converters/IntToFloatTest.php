<?php
/**
 * Created by PhpStorm.
 * User: execut
 * Date: 8/5/14
 * Time: 5:05 PM
 */

namespace execut\attributesConverter\converters;


use execut\TestCase;

class IntToFloatTest extends TestCase {
    public function testConvert() {
        $converter = new IntToFloat();
        $this->assertEquals(1.12, $converter->convert(112));
        $converter->decimals = 1;
        $this->assertEquals(11.2, $converter->convert(112));
    }
} 
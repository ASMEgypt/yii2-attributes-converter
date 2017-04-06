<?php
/**
 * Created by PhpStorm.
 * User: execut
 * Date: 8/25/14
 * Time: 11:58 AM
 */

namespace execut\attributesConverter\converters;


use execut\TestCase;

class DateTest extends TestCase {
    public function testConvert() {
        $converter = new Date();
        $converter->from = 'Y-m-d';
        $converter->to = 'Y.m.d';
        $this->assertEquals('2010.10.10', $converter->convert('2010-10-10'));
        $this->assertEquals(null, $converter->convert(null));
    }
} 
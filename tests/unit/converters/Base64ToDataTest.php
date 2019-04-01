<?php
/**
 * Created by PhpStorm.
 * User: execut
 * Date: 9/12/14
 * Time: 3:54 PM
 */

namespace execut\attributesConverter\converters;


use execut\TestCase;

class Base64ToDataTest extends TestCase {
    public function testConvert() {
        $data = base64_encode('data') . '
        ';
        $converter = new Base64ToData();
        $this->assertEquals('data', $converter->convert($data));
    }

    public function testIsBase64() {
        $data = base64_encode('data') . '
        ';
        $this->assertTrue(Base64ToData::isBase64($data));
        $this->assertFalse(Base64ToData::isBase64('data'));
    }
} 
<?php
/**
 * Created by PhpStorm.
 * User: execut
 * Date: 8/5/14
 * Time: 5:05 PM
 */

namespace execut\attributesConverter\converters;


use execut\TestCase;

class IpToIntTest extends TestCase {
    public function testConvert() {
        $converter = new IpToInt();
        $backConverter = new IntToIp();
        $ips = [
            '0.0.0.0',
            '0.0.0.1',
            '128.0.0.1',
            '128.0.255.1',
            '225.225.225.225',
            '225.225.225.224'
        ];
        foreach ($ips as $ip) {
            $result = $converter->convert($ip);
            $this->assertGreaterThanOrEqual(0, $result);
            $this->assertEquals($ip, $backConverter->convert($result));
        }
    }
} 
<?php

namespace execut\attributesConverter\converters;

use yii\base\Object;

class IpToInt extends Object implements Converter
{
    public function convert($ip) {
        if (($ip=ip2long($ip)) < 0){
            $ip += 4294967296;
        }

        return $ip;
    }
}


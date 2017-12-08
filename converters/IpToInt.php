<?php

namespace execut\attributesConverter\converters;

use yii\base\BaseObject;

class IpToInt extends BaseObject implements Converter
{
    public function convert($ip) {
        if (($ip=ip2long($ip)) < 0){
            $ip += 4294967296;
        }

        return $ip;
    }
}


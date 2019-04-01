<?php

namespace execut\attributesConverter\converters;

use yii\base\BaseObject;

class IntToIp extends BaseObject implements Converter
{
    public function convert($ip) {
        if ($ip > 4294967296) {
            $ip -= 4294967296;
        }

        return long2ip((int) $ip);
    }
}


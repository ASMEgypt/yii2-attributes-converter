<?php
namespace execut\attributesConverter\converters;

use yii\base\Object;

class Base64ToData extends Object implements Converter {
    public function convert($data) {
        return base64_decode($data);
    }

    public static function isBase64($data) {
        $data = trim($data, " \n\r");
        $len = strlen($data);
        if (substr($data, $len - 1, 1) === '=') {
            return true;
        } else {
            return false;
        }
    }
}
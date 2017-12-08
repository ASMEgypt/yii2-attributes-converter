<?php
namespace execut\attributesConverter\converters;

use yii\base\BaseObject;

class Base64ToData extends BaseObject implements Converter {
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
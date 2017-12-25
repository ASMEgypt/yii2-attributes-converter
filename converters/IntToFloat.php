<?php
/**
 * Created by PhpStorm.
 * User: execut
 * Date: 8/5/14
 * Time: 5:07 PM
 */

namespace execut\attributesConverter\converters;

use yii\base\BaseObject;

class IntToFloat extends BaseObject implements Converter {
    public $decimals = 2;
    public function convert($value) {
        $value = str_replace(',', '.', $value);
        return (float) ($value / pow(10, $this->decimals));
    }
}
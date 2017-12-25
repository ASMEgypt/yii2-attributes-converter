<?php
/**
 * Created by PhpStorm.
 * User: execut
 * Date: 8/5/14
 * Time: 5:07 PM
 */

namespace execut\attributesConverter\converters;

use yii\base\BaseObject;

class FloatToInt extends BaseObject implements Converter {
    public $decimals = 2;
    public function convert($value) {
        return (int) round($value * pow(10, $this->decimals));
    }
} 
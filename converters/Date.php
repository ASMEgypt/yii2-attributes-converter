<?php
namespace execut\attributesConverter\converters;
use DateTime;
use yii\base\BaseObject;

class Date extends BaseObject implements Converter {
    public $from = null;
    public $to = null;

    public function convert($value) {
        $date = DateTime::createFromFormat($this->from, $value);
        if (!$date) {
            return $value;
        }

        return $date->format($this->to);
    }
}
<?php
/**
 * Created by JetBrains PhpStorm.
 * User: execut
 * Date: 03.07.13
 * Time: 3:19
 * To change this template use File | Settings | File Templates.
 */

namespace execut\attributesAccessor\validator;


class Regex extends Validator {
    protected $_regex = null;
    public function setRegex($regex)
    {
        $this->_regex = $regex;
        return $this;
    }

    protected function _validate($value)
    {
        $regex = $this->_regex;

        return preg_match($regex, $value) !== 0;
    }
}
<?php
/**
 * Created by JetBrains PhpStorm.
 * User: execut
 * Date: 02.07.13
 * Time: 21:19
 * To change this template use File | Settings | File Templates.
 */

namespace execut\attributesAccessor\validator;


abstract class Validator {
    protected $_value = null;
    protected $_messages = null;
    public function setValue($value)
    {
        $this->_value = $value;

        $this->_messages = array();

        return $this;
    }

    public function validate()
    {
        if ($this->_value === null) {
            return true;
        }

        return $this->_validate($this->_value);
    }

    protected function _validate($value)
    {
        return true;
    }

    public function getMessages()
    {
        return $this->_messages;
    }

    public function isOnSet()
    {
        return true;
    }

    public function isOnGet()
    {
        return true;
    }
}
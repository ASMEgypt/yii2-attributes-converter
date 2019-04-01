<?php
/**
 * Created by JetBrains PhpStorm.
 * User: execut
 * Date: 03.07.13
 * Time: 3:19
 * To change this template use File | Settings | File Templates.
 */

namespace execut\attributesAccessor\validator;


class Callback extends Validator {
    protected $_callback = null;
    public function setCallback($callBack)
    {
        $this->_callback = $callBack;
        return $this;
    }

    protected function _validate($value)
    {
        $callback = $this->_callback;
        $messages = $callback($value);
        if ($messages === true) {
            $result = true;
        } else {
            $result = false;
            if (!is_array($messages)) {
                $messages = array($messages);
            }

            $this->_messages = $messages;
        }

        return $result;
    }
}
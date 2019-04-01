<?php
/**
 * Created by JetBrains PhpStorm.
 * User: execut
 * Date: 02.07.13
 * Time: 15:38
 * To change this template use File | Settings | File Templates.
 */

namespace execut\attributesAccessor\validator;


class Required extends Validator {
    public function validate()
    {
        $result = $this->_value !== null;
        if (!$result) {
            $this->_messages[] = 'Value is empty.';
        }

        return $result;
    }
}
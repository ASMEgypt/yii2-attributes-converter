<?php
/**
 * Created by JetBrains PhpStorm.
 * User: execut
 * Date: 27.06.13
 * Time: 1:36
 * To change this template use File | Settings | File Templates.
 */

namespace execut\attributesAccessor;
use execut\attributesAccessor\validator;
class RelationsFilter {
    protected $_aliases = array(
        'r' => 'Required',
    );
    public function filtrate($relation)
    {
        $typeValidator = new validator\Type();
        $validators = array(
            'type' => $typeValidator
        );
        $resultRelation = array();
        if (is_string($relation)) {
            $validatorsAliases = explode('+', $relation);
            $relation = array_shift($validatorsAliases);
            foreach ($validatorsAliases as $alias) {
                if ($name = $this->_getValidatorClassFromAlias($alias)) {
                    $class = "execut\attributesAccessor\\validator\\$name";

                    $validators[lcfirst($name)] = new $class;
                }
            }

            $typeValidator->setType($relation);
        } else {
            $typeValidator->setType($relation['type']);
            if (in_array('required', $relation)) {
                $validators['required'] = new validator\Required();
            }

            if (isset($relation['validators'])) {
                foreach ($relation['validators'] as $name => $validator) {
                    if (is_string($validator)) {
                        $name = ucfirst($validator);
                        $params = array();
                    } else if (is_array($validator)) {
                        if (isset($validator['type'])) {
                            $name = ucfirst($validator['type']);
                            unset($validator['type']);
                        } else {
                            $name = ucfirst($name);
                        }

                        $params = $validator;
                    } else {
                        $name = ucfirst($name);
                        $params = array();
                    }

                    $class = "\\execut\\attributesAccessor\\validator\\$name";

                    $validator = new $class;
                    foreach ($params as $key => $param) {
                        $methodName = 'set' . ucfirst($key);
                        $validator->$methodName($param);
                    }

                    $validators[lcfirst($name)] = $validator;
                }
            }

            if (isset($relation['default'])) {
                $resultRelation['default'] = $relation['default'];
            }
        }

        $resultRelation['validators'] = $validators;

        return $resultRelation;
    }

    protected function _getValidatorClassFromAlias($alias)
    {
        if (isset($this->_aliases[$alias])) {
            return $this->_aliases[$alias];
        }
    }
}
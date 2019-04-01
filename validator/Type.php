<?php
/**
 * Created by JetBrains PhpStorm.
 * User: execut
 * Date: 02.07.13
 * Time: 15:38
 * To change this template use File | Settings | File Templates.
 */

namespace execut\attributesAccessor\validator;


class Type extends Validator {
    protected $_types = array();
    protected $_nestedLevel = array();
    protected function _filtrateShortTypeName($type)
    {
        $types = array(
            'int' => 'integer',
            'str' => 'string',
            'bool' => 'boolean',
        );

        if (isset($types[$type])) {
            return $types[$type];
        }

        return $type;
    }
    public function isOnGet()
    {
        return false;
    }

    public function setType($type)
    {
        $typesList = explode('|', $type);
        $types = array();
        foreach ($typesList as $key => $type) {
            if (strpos($type, '[]') !== false) {
                $this->_nestedLevel[$key] = substr_count($type, '[]');
                $type = str_replace('[]', '', $type);
            } else {
                $this->_nestedLevel[$key] = 0;
            }

            $type = $this->_filtrateShortTypeName($type);

            $types[] = $type . str_repeat('[]', $this->_nestedLevel[$key]);
        }

        $this->_types = $types;
    }

    protected function _validate($value)
    {
        $realTypes = $this->_getRealTypes($value);
        $result = $this->_checkTypes($realTypes);
        if (!$result) {
            $this->_messages[] = 'Type "' . implode('|', $this->_types) . '" expected, real type is "' . implode('|', $realTypes) . '"';
        }

        return $result;
    }

    protected function _getRealTypes($value, $nestedLevel = 0, $types = array())
    {
        if (is_array($value)) {
            $nestedLevel++;
            if (count($value) == 0) {
                $realType = $this->_getType($value);
                if (!in_array($realType, $types)) {
                    $types[] = $realType;
                }
            } else {
                foreach ($value as $val) {
                    if (is_array($val)) {
                        $realTypes = $this->_getRealTypes($val, $nestedLevel, $types);
                        foreach ($realTypes as $realType) {
                            if (!in_array($realType, $types)) {
                                $types[] = $realType;
                            }
                        }
                    } else {
                        $realType = $this->_getType($val) . str_repeat('[]', $nestedLevel);
                        if (!in_array($realType, $types)) {
                            $types[] = $realType;
                        }
                    }
                }
            }
        } else {
            $realType = $this->_getType($value);
            if (!in_array($realType, $types)) {
                $types[] = $realType;
            }
        }

        return $types;
    }

    public function getTypes()
    {
        return $this->_types;
    }

    protected function _getType($value)
    {
        $type = gettype($value);
        if ($type === 'object') {
            $type = get_class($value);
        }

        return $type;
    }

    protected function _checkTypes($types)
    {
        $isValid = false;
        foreach ($types as $type) {
            $isValid = false;
            $nestedLevel = substr_count($type, '[]');
            $type = str_replace('[]', '', $type);
            foreach ($this->_types as $key => $requiredType) {
                if ($this->_nestedLevel[$key] === $nestedLevel) {
                    $requiredType = str_replace('[]', '', $requiredType);
                    if ($requiredType === $type) {
                        $isValid = true;
                    } else {
                        if (@class_exists($type) && (@class_exists($requiredType) || (@interface_exists($requiredType)))) {
                            if (is_subclass_of($type, $requiredType)) {
                                $isValid = true;
                            } else if (@interface_exists($requiredType)) {
                                $reflection = new \ReflectionClass($type);
                                if ($reflection->implementsInterface($requiredType)) {
                                    $isValid = true;
                                }
                            }
                        }
                    }
                }
            }

            if (!$isValid) {
                return false;
            }
        }

        return $isValid;
    }
}
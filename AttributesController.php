<?php
/**
 * Created by JetBrains PhpStorm.
 * User: execut
 * Date: 26.06.13
 * Time: 21:17
 * To change this template use File | Settings | File Templates.
 */

namespace execut\attributesAccessor;

class AttributesController {
    protected $_relations = array();
    protected $_attributes = array();
    protected $_relationsFilter = null;
    protected function _getRelationsFilter()
    {
        if ($this->_relationsFilter == null) {
            $this->_relationsFilter = new RelationsFilter();
        }

        return $this->_relationsFilter;
    }

    public function clearRelations() {
        $this->_relations = [];
    }

    public function setRelations($relations)
    {
        $this->clearRelations();
        foreach ($relations as $key => $relation) {
            $this->addRelation($key, $relation);
        }

        return $this;
    }

    public function addRelation($key, $relation) {
        $filter = $this->_getRelationsFilter();
        $this->_relations[$key] = $filter->filtrate($relation);

        return $this;
    }

    public function getRelations()
    {
        return $this->_relations;
    }

    public function setAttribute($name, $value)
    {
        if (is_array($value)
            // @TODO Протестировать
            && !empty($value)) {
            $attributeTypes = $this->_relations[$name]['validators']['type']->getTypes();
            foreach ($attributeTypes as $type) {
                if (@class_exists($type)) {
                    $object = new $type;
                    foreach ($value as $key => $v) {
                        $object->$key = $v;
                    }

                    $value = $object;
                }
            }
        }

        $this->_checkAttribute($name, $value);
        $this->_attributes[$name] = $value;
    }

    protected function _checkAttribute($name, $value)
    {
        $relation = $this->_relations[$name];
        $validators = $relation['validators'];
        foreach ($validators as $validator) {
            if ($validator->isOnSet()) {
                $validator->setValue($value);
                if (!$validator->validate()) {
                    $message = 'Error in attribute "' . $name . '": ' . implode('. ', $validator->getMessages()) . '.';

                    throw new \execut\attributesAccessor\Exception($message);
                }
            }
        }

        return true;
    }

    public function getAttribute($name)
    {
        if ($this->hasRelation($name)) {
            $this->_initAttributeDefaultValue($name);

            if (isset($this->_attributes[$name])) {
                $value = $this->_attributes[$name];
            } else {
                $value = null;
            }

            if (isset($this->_relations[$name]['validators'])) {
                foreach ($this->_relations[$name]['validators'] as $validator) {
                    if ($validator->isOnGet()) {
                        $validator->setValue($value);
                        if (!$validator->validate()) {
                            $message = 'Error in attribute "' . $name . '": ' . implode('. ', $validator->getMessages()) . '.';

                            throw new \execut\attributesAccessor\Exception($message);
                        }
                    }
                }
            }

            if (isset($this->_attributes[$name])) {
                return $this->_attributes[$name];
            }
        }
    }

    public function getAttributes($attributes = array())
    {
        $result = array();
        foreach ($attributes as $attribute) {
            $result[$attribute] = $this->getAttribute($attribute);
        }

        return $result;
    }

    public function hasRelation($name)
    {
        return isset($this->_relations[$name]);
    }

    /**
     * @param $name
     */
    protected function _initAttributeDefaultValue($name)
    {
        if ($this->hasRelation($name)) {
            if (!isset($this->_attributes[$name]) && array_key_exists('default', $this->_relations[$name])) {
                $defaultValue = $this->_relations[$name]['default'];
                if (is_callable($defaultValue)) {
                    $defaultValue = $defaultValue();
                } else if (@class_exists($defaultValue)) {
                    $defaultValue = new $defaultValue;
                }

                $this->setAttribute($name, $defaultValue);
            }
        }
    }
}
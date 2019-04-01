<?php
namespace execut\attributesAccessor;
/**
 * Created by JetBrains PhpStorm.
 * User: execut
 * Date: 26.06.13
 * Time: 20:27
 * To change this template use File | Settings | File Templates.
 */

trait AttributesAccessor {
    /**
     * @var AttributesController
     */
    protected $_attributesController = null;
    protected function _getAttributesController()
    {
        if ($this->_attributesController === null) {
            $controller = new AttributesController();
            $controller->setRelations($this->_getParsedRelations());
            $this->_attributesController = $controller;
        }

        return $this->_attributesController;
    }

    protected function _getParsedRelations()
    {
        $relations = $this->_getRelations();
        foreach ($relations as &$relation) {
            if (isset($relation['validators']['callback'])) {
                $callbackName = $relation['validators']['callback'];
                if (is_string($callbackName)) {
                    $callback = function ($value) use ($callbackName) {
                        return $this->$callbackName($value);
                    };
                } else {
                    $callback = $callbackName;
                }

                $relation['validators']['callback'] = array(
                    'callback' => $callback
                );
            }

            if (isset($relation['default']) && is_string($relation['default']) && method_exists($this, $relation['default'])) {
                $callbackName = $relation['default'];
                $relation['default'] = function () use ($callbackName) {
                    return $this->$callbackName();
                };
            }
        }

        return $relations;
    }

    public function __call($name,$parameters)
    {
        if (strpos($name, 'get') === 0) {
            $attribute = lcfirst(str_replace('get', '', $name));
            return $this->_getAttribute($attribute);
        }

        if (strpos($name, 'set') === 0) {
            $attribute = lcfirst(str_replace('set', '', $name));
            reset($parameters);
            return $this->_setAttribute($attribute, current($parameters));
        }

        throw new \ErrorException('Call to undefined method ' . get_class($this) . '::' . $name);
    }

    public function __get($param)
    {
        return $this->_getAttribute($param);
    }

    public function __set($param, $value)
    {
        return $this->_setAttribute($param, $value);
    }

    protected function _setAttribute($param, $value)
    {
        $setter = 'set' . ucfirst($param);
        if (method_exists($this, $setter)) {
            return $this->$setter($value);
        }

        $this->_getAttributesController()->setAttribute($param, $value);

        return $this;
    }

    protected function _getAttribute($name, $fromAttributesController = false)
    {
        if (!$fromAttributesController) {
            $getter = 'get' . ucfirst($name);
            if (method_exists($this, $getter)) {
                return $this->$getter();
            }
        }

        return $this->_getAttributesController()->getAttribute($name);
    }

    protected function _getAttributes($names)
    {
        return $this->_getAttributesController()->getAttributes($names);
    }

    public function __isset($name)
    {
        $getter = 'get' . ucfirst($name);
        if (method_exists($this, $getter)) {
            return true;
        }

        return $this->_getAttributesController()->hasRelation($name);
    }

    abstract protected function _getRelations();
}
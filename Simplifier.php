<?php
/**
 * Created by JetBrains PhpStorm.
 * User: execut
 * Date: 25.07.13
 * Time: 2:27
 * To change this template use File | Settings | File Templates.
 */

namespace execut\attributesAccessor;


abstract class Simplifier {
    use AttributesAccessor;
    protected $_relations;
    public function __construct($params = []) {
        foreach ($params as $attribute => $value) {
            $this->$attribute = $value;
        }
    }
    protected function _getRelations()
    {
        return $this->_relations;
    }
}
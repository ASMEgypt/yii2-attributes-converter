<?php
/**
 * Created by JetBrains PhpStorm.
 * User: execut
 * Date: 03.07.13
 * Time: 3:19
 * To change this template use File | Settings | File Templates.
 */

namespace execut\attributesAccessor\validator;


class Ip extends Regex {
    protected $_regex = '/^\d\d?\d?\.\d\d?\d?\.\d\d?\d?\.\d\d?\d?$/';
}
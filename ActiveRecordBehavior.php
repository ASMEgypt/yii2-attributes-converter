<?php
namespace execut\attributesConverter;
use yii\base\Behavior;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Expression;

/**
 * Created by PhpStorm.
 * User: execut
 * Date: 8/22/14
 * Time: 2:13 PM
 */
class ActiveRecordBehavior extends ByEventsBehavior {
    public $eventsPacks = [
        'from' => [
            BaseActiveRecord::EVENT_AFTER_INSERT,
            BaseActiveRecord::EVENT_AFTER_UPDATE,
            BaseActiveRecord::EVENT_AFTER_FIND,
        ],
        'to' => [

            BaseActiveRecord::EVENT_BEFORE_INSERT,
            BaseActiveRecord::EVENT_BEFORE_UPDATE,
        ]
    ];
}
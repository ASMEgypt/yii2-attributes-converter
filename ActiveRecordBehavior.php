<?php
namespace execut\attributesConverter;
use yii\base\Behavior;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
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

    protected function _convertFromEvent($event) {
        if (!empty($this->converters)) {
            $owner = $this->owner;
            foreach ($this->converters as $params) {
                $events = $params[0];
                if ((in_array($event->name, $events) || $this->_inEventsPacks($event->name, $events)) && (!isset($params[3]) || in_array($this->owner->scenario, $params[3]))) {
                    $attributes = $params[1];
                    $converters = $params[2];
                    foreach ($attributes as $attribute) {
                        if (!is_array($converters)) {
                            $converters = [$converters];
                        }

                        foreach ($converters as $converter) {
                            $value = $owner->$attribute;
                            if (in_array($event->name, $this->eventsPacks['to'])) {
                                if (!($owner instanceof ActiveRecord) || ($owner->isNewRecord || $owner->isAttributeChanged($attribute))) {
                                    $oldAttribute = $owner->getOldAttribute($attribute);
                                    $owner->$attribute = $this->_convert($converter, $value);
                                    $owner->setOldAttribute($attribute, $oldAttribute);
                                }
                            } else {
                                $owner->$attribute = $this->_convert($converter, $value);
                            }

                            if (in_array($event->name, $this->eventsPacks['from'])) {
                                if ($owner instanceof ActiveRecord && $owner->hasAttribute($attribute)) {
                                    $owner->setOldAttribute($attribute, $owner->$attribute);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
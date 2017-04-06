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
class ByEventsBehavior extends \yii\base\Behavior {
    public $eventsPacks = null;

    public $converters = null;
    public $scenarios = [];
    /**
     * @inheritdoc
     */
    public function events()
    {
        $events = [];
        if (!empty($this->eventsPacks)) {
            foreach ($this->eventsPacks as $packName => $pack) {
                foreach ($pack as $event) {
                    $events[] = $event;
                }
            }
        }

        if (!empty($this->converters)) {
            foreach ($this->converters as $params) {
                $converterEvents = $params[0];
                foreach ($converterEvents as $event) {
                    if (empty($this->eventsPacks) || !isset($this->eventsPacks[$event])) {
                        $events[] = $event;
                    }
                }
            }
        }

        return array_fill_keys($events, 'evaluateAttributes');
    }

    /**
     * Evaluates the attribute value and assigns it to the current attributes.
     * @param Event $event
     */
    public function evaluateAttributes($event)
    {
        $this->_convertFromEvent($event);
    }

    protected function _inEventsPacks($event, $packs) {
        foreach ($this->eventsPacks as $pack => $events) {
            if (in_array($pack, $packs) && in_array($event, $events)) {
                return true;
            }
        }
    }

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
                            $owner->$attribute = $this->_convert($converter, $value);
                        }
                    }
                }
            }
        }
    }

    protected function _convertFromPacks($event)
    {
        foreach ($this->eventsPacks as $packName => $events) {
            if (in_array($event->name, $events)) {
                foreach ($this->converters as $attribute => $converters) {
                    $owner = $this->owner;
                    $value = $owner->$attribute;
                    if (isset($converters[$packName]) && !empty($value)) {
                        $converter = $converters[$packName];
                        $owner->$attribute = $this->_convert($converter, $value);
                    }
                }
            }
        }
    }

    /**
     * @param $converter
     * @param $value
     */
    protected function _convert($converter, $value)
    {
        if (!empty($value)) {
            if ($converter instanceof \Closure) {
                $value = $converter($value);
            } else {
                $value = $converter->convert($value);
            }
        }

        return $value;
    }
}
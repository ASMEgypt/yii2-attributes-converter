<?php
/**
 * Created by PhpStorm.
 * User: execut
 * Date: 8/22/14
 * Time: 1:37 PM
 */

namespace execut\attributesConverter;

use execut\TestCase;
use yii\base\Event;
use yii\db\BaseActiveRecord;

class TestComponent {
    public $convertedAttribute;
}

class ByEventsBehaviorTest extends TestCase {
    public function testConvertByPack() {
        $converter = new ByEventsBehavior();
        $converter->eventsPacks = [
            'from' => [
                BaseActiveRecord::EVENT_AFTER_INSERT,
            ],
        ];
        $converter->converters = [
            [
                ['from', BaseActiveRecord::EVENT_AFTER_FIND],
                ['convertedAttribute'],
                [
                    function ($v) {
                        return $v . 'converted';
                    },
                ]
            ]
        ];

        $expectedEvents = [
            BaseActiveRecord::EVENT_AFTER_INSERT => 'evaluateAttributes',
            BaseActiveRecord::EVENT_AFTER_FIND => 'evaluateAttributes',
        ];
        $this->assertEquals($expectedEvents, $converter->events());

        $owner = new TestComponent();
        $owner->convertedAttribute = 'test';
        $converter->owner = $owner;

        $event = new Event();
        $event->name = BaseActiveRecord::EVENT_AFTER_INSERT;

        $converter->evaluateAttributes($event);
        $this->assertEquals('testconverted', $owner->convertedAttribute);

        $event->name = BaseActiveRecord::EVENT_AFTER_FIND;

        $converter->evaluateAttributes($event);
        $this->assertEquals('testconvertedconverted', $owner->convertedAttribute);
    }
} 
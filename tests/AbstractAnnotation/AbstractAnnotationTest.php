<?php

/**
 * AbstractAnnotationTest.php
 * @author Jack Worman
 */

namespace JWorman\AnnotationReader\Tests\AbstractAnnotation;

use JWorman\AnnotationReader\AbstractAnnotation;
use JWorman\AnnotationReader\Tests\Unit\Annotations\Annotation1;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractAnnotationTest
 * @package JWorman\AnnotationReader\Tests\AbstractAnnotation
 */
class AbstractAnnotationTest extends TestCase
{
    /**
     * @covers       \JWorman\AnnotationReader\AbstractAnnotation::__construct
     * @dataProvider provideDataForTestConstruct
     * @param string $jsonValue
     * @param mixed $expectedValue
     */
    public function testConstruct($jsonValue, $expectedValue)
    {
        $abstractAnnotation = new Annotation1($jsonValue);

        $this->assertEquals($expectedValue, $abstractAnnotation->getValue());
    }

    /**
     * @return array
     */
    public function provideDataForTestConstruct()
    {
        return array(
            array('null', null),
            array('false', false),
            array('true', true),
            array('"fizzbuzz"', 'fizzbuzz'),
            array('[]', array()),
            array('{}', new \stdClass()),
            array(
                '[null, false, true, "fizzbuzz", [], {}]',
                array(null, false, true, "fizzbuzz", array(), new \stdClass())
            )
        );
    }

    /**
     * @covers       \JWorman\AnnotationReader\AbstractAnnotation::__construct
     * @dataProvider provideDataForTestConstructException
     * @param $jsonValue
     */
    public function testConstructException($jsonValue)
    {
        $this->expectException('InvalidArgumentException');
        new Annotation1($jsonValue);
    }

    /**
     * @return array
     */
    public function provideDataForTestConstructException()
    {
        return array(
            array('nulls'),
            array('falses'),
            array('["boomba"'),
        );
    }
}
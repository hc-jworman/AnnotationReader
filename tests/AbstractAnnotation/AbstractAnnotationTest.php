<?php

/**
 * AbstractAnnotationTest.php
 * @author Jack Worman
 */

namespace JWorman\AnnotationReader\Tests\AbstractAnnotation;

use JWorman\AnnotationReader\Exceptions\PropertyDoesNotExist;
use JWorman\AnnotationReader\AbstractAnnotation;
use JWorman\AnnotationReader\Tests\AbstractAnnotation\Annotations\TestConstructAnnotation;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractAnnotationTest
 * @package JWorman\AnnotationReader\Tests\AbstractAnnotation
 */
class AbstractAnnotationTest extends TestCase
{
    /**
     * @covers \JWorman\AnnotationReader\AbstractAnnotation::__construct
     */
    public function testConstruct()
    {
        $annotation = new TestConstructAnnotation('[null, false, true, 42, 3.14, "fizzbuzz", [], {}]');

        $this->assertInstanceOf(AbstractAnnotation::CLASS_NAME, $annotation);
        $this->assertEquals(
            array(null, false, true, 42, 3.14, "fizzbuzz", array(), new \stdClass()),
            $annotation->getValue()
        );
    }

    /**
     * @covers \JWorman\AnnotationReader\AbstractAnnotation::__construct
     */
    public function testConstructInvalidJson()
    {
        $this->expectException('InvalidArgumentException');

        new TestConstructAnnotation('invalid json');
    }

    /**
     * @covers \JWorman\AnnotationReader\AbstractAnnotation::mapObjectValuesToProperties
     */
    public function testMapObjectValuesToProperties()
    {
        $annotation = new TestConstructAnnotation('{"property1": "fizzbuzz", "property2": 42}');

        $this->assertEquals('fizzbuzz', $annotation->getProperty1());
        $this->assertEquals(42, $annotation->getProperty2());
    }

    /**
     * @covers \JWorman\AnnotationReader\AbstractAnnotation::mapObjectValuesToProperties
     */
    public function testMapObjectValuesToPropertiesPropertyDoesNotExist()
    {
        $this->expectException(PropertyDoesNotExist::CLASS_NAME);

        new TestConstructAnnotation('{"propertyDoesNotExist": "fizzbuzz", "property2": 42}');
    }
}
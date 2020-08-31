<?php

namespace JWorman\AnnotationReader\Tests\AnnotationReader;

use JWorman\AnnotationReader\AnnotationReader;
use JWorman\AnnotationReader\Tests\AnnotationReader\Annotations\Annotation1;
use JWorman\AnnotationReader\Tests\AnnotationReader\Entities\Entity1;
use PHPUnit\Framework\TestCase;

class AnnotationReaderTest extends TestCase
{
    /**
     * @covers \JWorman\AnnotationReader\AnnotationReader::getPropertyAnnotation
     */
    public function testGetPropertyAnnotation()
    {
        $reflectionProperty = new \ReflectionProperty(Entity1::CLASS_NAME, 'property1');
        $annotationReader = new AnnotationReader();

        $start = microtime(true);
        /** @var Annotation1 $annotation1 */
        $annotation1 = $annotationReader->getPropertyAnnotation($reflectionProperty, Annotation1::CLASS_NAME);
        $withoutCache = microtime(true) - $start;
        $this->assertEquals('fizzbuzz', $annotation1->getValue());

        // Using cache:
        $start = microtime(true);
        $annotation1 = $annotationReader->getPropertyAnnotation($reflectionProperty, Annotation1::CLASS_NAME);
        $withCache = microtime(true) - $start;
        $this->assertEquals('fizzbuzz', $annotation1->getValue());

        $this->assertTrue($withCache < $withoutCache);
    }

    /**
     * @dataProvider provideForTestAnnotationRegex
     * @param string[] $expectedNames
     * @param string[] $expectedValues
     */
    public function testAnnotationRegex($expectedNames, $expectedValues)
    {
        $reflectionProperty = new \ReflectionProperty(Entity1::CLASS_NAME, 'property2');
        preg_match_all('/@([\\\\\w]+)\((?:|(.*?[]"}]))\)/', $reflectionProperty->getDocComment(), $matches);
        $this->assertEquals($expectedNames, $matches[1]);
        $this->assertEquals($expectedValues, $matches[2]);
    }

    /**
     * @return \string[][][]
     */
    public function provideForTestAnnotationRegex()
    {
        return array(
            array(
                array(
                    'Ann1',
                    'Ann2',
                    'Ann3',
                    'Ann4',
                    'Ann5',
                    'Ann6',
                    'Ann7',
                    'Ann8',
                    'Ann9',
                    'Ann10\Name',
                    'Ann11',
                    'Ann12\Filter\Name',
                    'Ann13'
                ),
                array(
                    '',
                    '"var1"',
                    '"var1"',
                    '"var1"',
                    '"var1"',
                    '"var1"',
                    '"var1"',
                    '"name@name.com"',
                    '"name()"',
                    '"var1", "var2"',
                    '["var1", "var2"], "var3"',
                    '["var1", "var2"], "var3", {"var4": "var5"}',
                    '"name() ", "aaa"'
                )
            )
        );
    }

//    public function testBlasssss()
//    {
//        $reflectionProperty = new \ReflectionProperty(Entity1::CLASS_NAME, 'property1');
//        $annotationReader = new AnnotationReader();
//
//        $annotation1 = $annotationReader->getPropertyAnnotation($reflectionProperty, Annotation1::CLASS_NAME . '5');
//
//    }
}

<?php

/**
 * AnnotationReaderTest.php
 * @author Jack Worman
 */

namespace JWorman\AnnotationReader\Tests\Unit;

use JWorman\AnnotationReader\AnnotationReader;
use JWorman\AnnotationReader\Exceptions\AnnotationReaderException;
use JWorman\AnnotationReader\Tests\Unit\Annotations\Annotation1;
use JWorman\AnnotationReader\Tests\Unit\Entities\Entity1;
use PHPUnit\Framework\TestCase;

class AnnotationReaderTest extends TestCase
{
    /**
     * @covers AnnotationReader::getPropertyAnnotation
     * @throws AnnotationReaderException
     */
    public function testGetPropertyAnnotation()
    {
        $reflectionProperty = new \ReflectionProperty(Entity1::CLASS_NAME, 'property1');
        /** @var Annotation1 $annotation1 */
        $annotation1 = AnnotationReader::getPropertyAnnotation($reflectionProperty, Annotation1::CLASS_NAME);
        $this->assertEquals('fizzbuzz', $annotation1->getValue());
    }

    /**
     * @return array[]
     */
    public function provideForTestGetStartPositionOfAnnotation()
    {
        $reflectionProperty = new \ReflectionProperty(Entity1::CLASS_NAME, 'property1');
        $docComment = $reflectionProperty->getDocComment();

        return array(
            array($docComment, array('DoesNotExist'), false),
            array($docComment, array('Annotation1'), 25),
        );
    }

    /**
     * @covers AnnotationReader::getImportAliases
     */
    public function testGetImportAliases()
    {
        $reflectionMethod = new \ReflectionMethod('\JWorman\AnnotationReader\AnnotationReader', 'getImportAliases');
        $reflectionMethod->setAccessible(true);
        $refactorUseStatements = $reflectionMethod->invoke(null, new \ReflectionClass(Entity1::CLASS_NAME));

        $expectedNamespaceSubsets = array(
            'Annotation1' => 'JWorman\AnnotationReader\Tests\Unit\Annotations\Annotation1',
            'Blah' => 'JWorman\AnnotationReader\Tests\Unit'
        );
        $this->assertEquals($expectedNamespaceSubsets, $refactorUseStatements);
    }

    /**
     * https://regex101.com/r/tW6pI4/1
     *
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
                array('Ann1', 'Ann2', 'Ann3', 'Ann4', 'Ann5', 'Ann6', 'Ann7', 'Ann8', 'Ann9', 'Ann10\Name', 'Ann11', 'Ann12\Filter\Name', 'Ann13'),
                array('', '"var1"', '"var1"', '"var1"', '"var1"', '"var1"', '"var1"', '"name@name.com"', '"name()"', '"var1", "var2"', '["var1", "var2"], "var3"', '["var1", "var2"], "var3", {"var4": "var5"}', '"name() ", "aaa"')
            )
        );
    }
}

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
     * @covers AnnotationReader::getStartPositionOfAnnotation
     * @dataProvider provideForTestGetStartPositionOfAnnotation
     * @param string $docComment
     * @param string[] $namePatterns
     * @param int|false $expectedStartPosition
     */
    public function testGetStartPositionOfAnnotation($docComment, $namePatterns, $expectedStartPosition)
    {
        $reflectionMethod = new \ReflectionMethod(AnnotationReader::CLASS_NAME, 'getStartPositionOfAnnotation');
        $reflectionMethod->setAccessible(true);
        $startPosition = $reflectionMethod->invoke(null, $docComment, $namePatterns);

        $this->assertEquals($expectedStartPosition, $startPosition);
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
     * @covers AnnotationReader::getNamespaceSubsets
     */
    public function testGetNamespaceSubsets()
    {
        $reflectionMethod = new \ReflectionMethod(AnnotationReader::CLASS_NAME, 'getNamespaceSubsets');
        $reflectionMethod->setAccessible(true);
        $namespaceSubsets = $reflectionMethod->invoke(null, Annotation1::CLASS_NAME);

        $expectedNamespaceSubsets = array(
            'JWorman\AnnotationReader\Tests\Unit\Annotations\Annotation1',
            'AnnotationReader\Tests\Unit\Annotations\Annotation1',
            'Tests\Unit\Annotations\Annotation1',
            'Unit\Annotations\Annotation1',
            'Annotations\Annotation1',
            'Annotation1'
        );
        $this->assertEquals($expectedNamespaceSubsets, $namespaceSubsets);
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
     * @covers AnnotationReader::getNamePatterns
     */
    public function testGetNamePatterns()
    {
        $reflectionMethod = new \ReflectionMethod(AnnotationReader::CLASS_NAME, 'getNamePatterns');
        $reflectionMethod->setAccessible(true);
        $importAliases = array(
            'Annotation1' => 'JWorman\AnnotationReader\Tests\Unit\Annotations\Annotation1',
            'Blah' => 'JWorman\AnnotationReader\Tests\Unit'
        );
        $namePatterns = $reflectionMethod->invoke(null, Annotation1::CLASS_NAME, $importAliases);

        $expectedNamePatterns = array(
            'Annotation1',
            'Blah\Annotations\Annotation1'
        );
        $this->assertEquals($expectedNamePatterns, $namePatterns);
    }
}

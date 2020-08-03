<?php

/**
 * FileParserTest.php
 * @author Jack Worman
 */

namespace JWorman\AnnotationReader\Tests\FileParser;

use JWorman\AnnotationReader\FileParser;
use JWorman\AnnotationReader\Tests\Unit\Annotations\Annotation1;
use JWorman\AnnotationReader\Tests\Unit\Entities\GetAnnotationDataFromDocCommentTest;
use JWorman\AnnotationReader\Tests\Unit\Entities\GetImportsTest;
use PHPUnit\Framework\TestCase;

/**
 * Class FileParserTest
 * @package JWorman\AnnotationReader\Tests\Unit
 */
class FileParserTest extends TestCase
{
    /**
     * @covers \JWorman\AnnotationReader\FileParser::getClassImports
     */
    public function testGetImportAliases()
    {
        $reflectionClass = new \ReflectionClass(GetImportsTest::CLASS_NAME);
        $fileParser = new FileParser($reflectionClass);
        $reflectionMethod = new \ReflectionMethod(FileParser::CLASS_NAME, 'getClassImports');
        $reflectionMethod->setAccessible(true);
        $classImports = $reflectionMethod->invoke($fileParser);

        $expectedClassImports = array(
            'Name' => 'A\Fully\Qualified\Name',
            'ThisIsAnAlias' => 'Has\An\Alias',
            'First' => 'First\Multiple',
            'Multiple' => 'Second\Multiple',
            'Third' => 'Third',
            'Fourth' => 'Fourth\Multiple',
            'ClassA' => 'Group\Names\ClassA',
            'B' => 'Group\Names\ClassB',
            'C' => 'Group\Names\ClassC',
            'Bottom' => 'At\The\Bottom'
        );
        $this->assertEquals($expectedClassImports, $classImports);
        // TODO: assert cache variable is set
    }

    /**
     * @covers \JWorman\AnnotationReader\FileParser::getAnnotationDataFromDocComment
     */
    public function testGetAnnotationDataFromDocComment()
    {
        $reflectionClass = new \ReflectionClass(GetAnnotationDataFromDocCommentTest::CLASS_NAME);
        $fileParser = new FileParser($reflectionClass);
        $docComment = $reflectionClass->getProperty('testProperty')->getDocComment();
        $annotationData = $fileParser->getAnnotationDataFromDocComment($docComment);

        $expectedAnnotationData = array(
            Annotation1::CLASS_NAME => '"fizzbuzz"',
//            'JWorman\AnnotationReader\Tests\Unit\Entities\FakeAnnotation' => ''
        );
        $this->assertEquals($expectedAnnotationData, $annotationData);
    }
}
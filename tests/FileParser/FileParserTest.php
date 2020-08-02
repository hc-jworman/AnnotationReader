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
        $reflectionMethod = new \ReflectionMethod(FileParser::CLASS_NAME, 'getClassImports');
        $reflectionMethod->setAccessible(true);
        $classImports = $reflectionMethod->invoke(
            new FileParser(new \ReflectionClass(GetImportsTest::CLASS_NAME))
        );

        $expectedClassImports = array(
            'Name' => 'A\Fully\Qualified\Name',
            'ThisIsAnAlias' => 'Has\An\Alias'
        );
        $this->assertEquals($expectedClassImports, $classImports);
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
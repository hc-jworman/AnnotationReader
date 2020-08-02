<?php

/**
 * AnnotationFactoryTest.php
 * @author Jack Worman
 */

namespace JWorman\AnnotationReader\Tests\AnnotationFactory;

use JWorman\AnnotationReader\AnnotationFactory;
use JWorman\AnnotationReader\AbstractAnnotation;
use JWorman\AnnotationReader\Tests\Unit\Annotations\Annotation1;
use JWorman\AnnotationReader\Tests\Unit\Annotations\Annotation2;
use JWorman\AnnotationReader\Tests\Unit\Entities\Entity1;
use PHPUnit\Framework\TestCase;

/**
 * Class AnnotationFactoryTest
 * @package JWorman\AnnotationReader\Tests\AnnotationFactory
 */
class AnnotationFactoryTest extends TestCase
{
    /**
     * @covers       \JWorman\AnnotationReader\AnnotationFactory::create
     * @dataProvider provideForTestCreate
     * @param string $annotationName
     * @param bool $succeeds
     */
    public function testCreate($annotationName, $succeeds)
    {
        $annotation = AnnotationFactory::create($annotationName, 'true');

        if ($succeeds) {
            $this->assertTrue($annotation instanceof $annotationName);
            $this->assertTrue($annotation instanceof AbstractAnnotation);
            $this->assertTrue($annotation->getValue());
        } else {
            $this->assertNull($annotation);
        }
    }

    /**
     * @return array[]
     */
    public function provideForTestCreate()
    {
        return array(
            array(Annotation1::CLASS_NAME, true),
//            array('DoesNotExist', false),
        );
    }

    /**
     * @covers       \JWorman\AnnotationReader\AnnotationFactory::batchCreate
     * @dataProvider provideForTestBatchCreate
     * @param array $annotationData
     * @param int $expectedCount
     */
    public function testBatchCreate(array $annotationData, $expectedCount)
    {
        $annotations = AnnotationFactory::batchCreate($annotationData);

        $this->assertCount($expectedCount, $annotations);
        foreach($annotations as $annotation) {
            $this->assertTrue($annotation instanceof AbstractAnnotation);
            $this->assertTrue($annotation->getValue());
        }
    }

    /**
     * @return array
     */
    public function provideForTestBatchCreate()
    {
        return array(
            // Test Case 1
            array(
                array(Annotation1::CLASS_NAME => 'true', Annotation2::CLASS_NAME => 'true'),
                2
            ),
            // Test Case 2
//            array(
//                array(
//                    Annotation1::CLASS_NAME => 'true',
//                    null,
//                    false,
//                    true,
//                    'DoesNotExist' => 'true',
//                    Entity1::CLASS_NAME => 'true',
//                    new Entity1()
//                ),
//                1
//            ),
            // Test Case 2
//            array(
//                array(),
//                0
//            ),
        );
    }
}
<?php

/**
 * AnnotationFactoryTest.php
 * @author Jack Worman
 */

namespace JWorman\AnnotationReader\Tests\AnnotationFactory;

use JWorman\AnnotationReader\AnnotationFactory;
use JWorman\AnnotationReader\AbstractAnnotation;
use JWorman\AnnotationReader\Exceptions\NotAnAnnotation;
use JWorman\AnnotationReader\Tests\AnnotationFactory\Annotations\ClassIsNotAnAnnotation;
use JWorman\AnnotationReader\Tests\AnnotationFactory\Annotations\TestBatchCreateAnnotation1;
use JWorman\AnnotationReader\Tests\AnnotationFactory\Annotations\TestBatchCreateAnnotation2;
use JWorman\AnnotationReader\Tests\AnnotationFactory\Annotations\TestCreateAnnotation;
use JWorman\AnnotationReader\Tests\Unit\Annotations\Annotation1;
use JWorman\AnnotationReader\Tests\Unit\Annotations\Annotation2;
use PHPUnit\Framework\TestCase;

/**
 * Class AnnotationFactoryTest
 * @package JWorman\AnnotationReader\Tests\AnnotationFactory
 */
class AnnotationFactoryTest extends TestCase
{
    /**
     * @covers \JWorman\AnnotationReader\AnnotationFactory::create
     * @throws \ReflectionException
     */
    public function testCreate()
    {
        $annotation = AnnotationFactory::create(TestCreateAnnotation::CLASS_NAME, 'true');

        $this->assertInstanceOf(TestCreateAnnotation::CLASS_NAME, $annotation);
        $this->assertInstanceOf(AbstractAnnotation::CLASS_NAME, $annotation);
        $this->assertTrue($annotation->getValue());
    }

    /**
     * @covers \JWorman\AnnotationReader\AnnotationFactory::create
     * @throws \ReflectionException
     */
    public function testCreateClassDoesNotExist()
    {
        $this->expectException('\ReflectionException');

        AnnotationFactory::create('ClassDoesNotExist', 'true');
    }

    /**
     * @covers \JWorman\AnnotationReader\AnnotationFactory::create
     * @throws \ReflectionException
     */
    public function testCreateClassIsNotAnAnnotation()
    {
        $this->expectException(NotAnAnnotation::CLASS_NAME);

        AnnotationFactory::create(ClassIsNotAnAnnotation::CLASS_NAME, 'true');
    }

    /**
     * @covers \JWorman\AnnotationReader\AnnotationFactory::batchCreate
     * @throws \ReflectionException
     */
    public function testBatchCreate()
    {
        $annotationData = array(
            TestBatchCreateAnnotation1::CLASS_NAME => 'true',
            TestBatchCreateAnnotation2::CLASS_NAME => 'true'
        );
        $annotations = AnnotationFactory::batchCreate($annotationData);

        $this->assertCount(2, $annotations);
        foreach($annotations as $annotation) {
            $this->assertTrue($annotation instanceof AbstractAnnotation);
            $this->assertTrue($annotation->getValue());
        }
    }
}

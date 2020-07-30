<?php

/**
 * GetAnnotationDataFromDocCommentTest.php
 * @author Jack Worman
 */

namespace JWorman\AnnotationReader\Tests\Unit\Entities;

use JWorman\AnnotationReader\Tests\Unit\Annotations\Annotation1;

/**
 * Class GetAnnotationDataFromDocCommentTest
 * @package JWorman\AnnotationReader\Tests\Unit\Entities
 */
class GetAnnotationDataFromDocCommentTest
{
    const CLASS_NAME = __CLASS__;

    /**
     * @var string
     * @Annotation1("fizzbuzz")
     * @FakeAnnotation()
     */
    private $testProperty;
}
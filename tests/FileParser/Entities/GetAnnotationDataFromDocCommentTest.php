<?php

/**
 * GetAnnotationDataFromDocCommentTest.php
 * @author Jack Worman
 */

namespace JWorman\AnnotationReader\Tests\FileParser\Entities;

use JWorman\AnnotationReader\Tests\FileParser\Annotations\Annotation1;
use JWorman\AnnotationReader\Tests\FileParser\Annotations\Annotation2;

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
     * @Annotation2([null, true, false])
     */
    private $testProperty;
}
<?php

/**
 * Annotation2.php
 * @author Jack Worman
 */

namespace JWorman\AnnotationReader\Tests\AnnotationReader\Annotations;

use JWorman\AnnotationReader\AbstractAnnotation;

/**
 * Class Annotation2
 * @package JWorman\AnnotationReader\Tests\Unit\Annotations
 */
class Annotation2 extends AbstractAnnotation
{
    const CLASS_NAME = __CLASS__;

    /**
     * @inheritDoc
     */
    public function validateValue()
    {
        return true;
    }
}
<?php

/**
 * Annotation1.php
 * @author Jack Worman
 */

namespace JWorman\AnnotationReader\Tests\AnnotationReader\Annotations;

use JWorman\AnnotationReader\AbstractAnnotation;

/**
 * Class Annotation1
 * @package JWorman\AnnotationReader\Tests\Unit\Annotations
 */
class Annotation1 extends AbstractAnnotation
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

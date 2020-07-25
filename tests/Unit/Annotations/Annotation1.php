<?php

/**
 * Annotation1.php
 * @author Jack Worman
 */

namespace JWorman\AnnotationReader\Tests\Unit\Annotations;

use JWorman\AnnotationReader\AbstractAnnotation;

/**
 * Class Annotation1
 * @package JWorman\AnnotationReader\Tests\Unit\Annotations
 */
class Annotation1 extends AbstractAnnotation
{
    const CLASS_NAME = __CLASS__;

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}

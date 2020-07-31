<?php

/**
 * TestConstruct.php
 * @author Jack Worman
 */

namespace JWorman\AnnotationReader\Tests\AbstractAnnotation\Annotations;

use JWorman\AnnotationReader\AbstractAnnotation;

/**
 * Class TestConstruct
 * @package JWorman\AnnotationReader\Tests\AbstractAnnotation\Annotations
 */
class TestConstruct extends AbstractAnnotation
{
    /**
     * @inheritDoc
     */
    public function validateValue()
    {
        return gettype($this->value) === 'boolean';
    }
}
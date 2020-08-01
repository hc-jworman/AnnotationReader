<?php

/**
 * TestConstructAnnotation.php
 * @author Jack Worman
 */

namespace JWorman\AnnotationReader\Tests\AbstractAnnotation\Annotations;

use JWorman\AnnotationReader\AbstractAnnotation;

/**
 * Class TestConstructAnnotation
 * @package JWorman\AnnotationReader\Tests\AbstractAnnotation\Annotations
 */
class TestConstructAnnotation extends AbstractAnnotation
{
    private $property1;

    private $property2;

    /**
     * @return mixed
     */
    public function getProperty1()
    {
        return $this->property1;
    }

    /**
     * @return mixed
     */
    public function getProperty2()
    {
        return $this->property2;
    }
}
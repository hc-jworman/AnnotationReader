<?php

/**
 * AbstractAnnotation.php
 * @author Jack Worman
 */

namespace JWorman\AnnotationReader;

/**
 * Class AbstractAnnotation
 * @package JWorman\AnnotationReader\AnnotationReader
 */
abstract class AbstractAnnotation
{
    /**
     * @var string
     */
    protected $value;

    /**
     * AbstractAnnotation constructor.
     * @param $value
     */
    final public function __construct($value)
    {
        $this->value = $value;
    }
}

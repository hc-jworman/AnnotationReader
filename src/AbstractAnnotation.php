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
     * @var mixed
     */
    protected $value;

    /**
     * AbstractAnnotation constructor.
     * @param string $value
     */
    final public function __construct($value)
    {
        $this->value = json_decode($value);
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}

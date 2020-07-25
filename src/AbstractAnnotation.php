<?php

/**
 * AbstractAnnotation.php
 * @author Jack Worman
 */

namespace App;

/**
 * Class AbstractAnnotation
 * @package App\AnnotationReader
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

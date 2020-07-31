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
    const CLASS_NAME = __CLASS__;

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
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException("Invalid JSON in annotation: $value");
        }
        if (!$this->validateValue()) {
            throw new \InvalidArgumentException('Annotation has invalid value.');
        }
    }

    /**
     * @return mixed
     */
    final public function getValue()
    {
        return $this->value;
    }

    /**
     * @return bool
     */
    abstract public function validateValue();
}

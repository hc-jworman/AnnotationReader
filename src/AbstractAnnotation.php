<?php

/**
 * AbstractAnnotation.php
 * @author Jack Worman
 */

namespace JWorman\AnnotationReader;

use JWorman\AnnotationReader\Exceptions\AnnotationReaderException;

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
     * @throws AnnotationReaderException
     */
    final public function __construct($value)
    {
        $this->value = json_decode($value);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new AnnotationReaderException('Invalid JSON in annotation.');
        }
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}

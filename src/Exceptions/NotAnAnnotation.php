<?php

/**
 * NotAnAnnotation.php
 * @author Jack Worman
 */

namespace JWorman\AnnotationReader\Exceptions;

/**
 * Class NotAnAnnotation
 * @package JWorman\AnnotationReader\Exceptions
 */
class NotAnAnnotation extends \InvalidArgumentException
{
    /**
     * @var string
     */
    private $annotationName;

    /**
     * AnnotationNotFound constructor.
     * @param string $annotationName
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct(
        $annotationName,
        $code = 0,
        \Exception $previous = null
    ) {
        $this->annotationName = $annotationName;

        $message = "Class \"$annotationName\" is not a child of " . AbstractAnnotation::CLASS_NAME;
        parent::__construct($message, $code, $previous);
    }
}
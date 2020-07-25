<?php

/**
 * NoDocCommentException.php
 * @author Jack Worman
 */

namespace JWorman\AnnotationReader\Exceptions;

/**
 * Class MissingDocComment
 * @package JWorman\AnnotationReader\Utilities\AnnotationReader\Exceptions
 */
class NoDocCommentException extends AnnotationReaderException
{
    /**
     * MissingDocComment constructor.
     * @param \ReflectionProperty $reflectionProperty
     */
    public function __construct($reflectionProperty)
    {
        $message = sprintf(
            'Property "%s" in class "%s" does not have a doc comment.',
            $reflectionProperty->getName(),
            $reflectionProperty->getDeclaringClass()->getNamespaceName()
        );
        parent::__construct($message);
    }
}

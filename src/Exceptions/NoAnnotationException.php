<?php

/**
 * NoAnnotationException.php
 * @author Jack Worman
 */

namespace JWorman\AnnotationReader\Exceptions;

/**
 * Class NoAnnotationException
 * @package JWorman\AnnotationReader\Utilities\AnnotationReader\Exceptions
 */
class NoAnnotationException extends AnnotationReaderException
{
    /**
     * MissingDocComment constructor.
     * @param \ReflectionProperty $reflectionProperty
     * @param string $annotation
     */
    public function __construct($reflectionProperty, $annotation)
    {
        $message = sprintf(
            'Annotation "%s" does not exist on property "%s" in class "%s".',
            $annotation,
            $reflectionProperty->getName(),
            $reflectionProperty->getDeclaringClass()->getNamespaceName()
        );
        parent::__construct($message);
    }
}

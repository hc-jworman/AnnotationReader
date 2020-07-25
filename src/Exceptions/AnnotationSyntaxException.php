<?php

/**
 * AnnotationSyntaxException.php
 * @author Jack Worman
 */

namespace App\Exceptions;

/**
 * Class AnnotationSyntaxException
 * @package App\Utilities\AnnotationReader\Exceptions
 */
class AnnotationSyntaxException extends AnnotationReaderException
{
    /**
     * MissingDocComment constructor.
     * @param \ReflectionProperty $reflectionProperty
     * @param string $annotation
     */
    public function __construct($reflectionProperty, $annotation)
    {
        $message = sprintf(
            'Annotation "%s" on property "%s" in class "%s" has invalid syntax.',
            $annotation,
            $reflectionProperty->getName(),
            $reflectionProperty->getDeclaringClass()->getNamespaceName()
        );
        parent::__construct($message);
    }
}

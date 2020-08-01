<?php

/**
 * PropertyAnnotationNotFound.php
 * @author Jack Worman
 */

namespace JWorman\AnnotationReader;

/**
 * Class AnnotationNotFound
 * @package JWorman\AnnotationReader
 */
class PropertyAnnotationNotFound extends \Exception
{
    /**
     * @var \ReflectionProperty
     */
    private $reflectionProperty;

    /**
     * @var string
     */
    private $annotationName;

    /**
     * AnnotationNotFound constructor.
     * @param \ReflectionProperty $reflectionProperty
     * @param string $annotationName
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct(
        \ReflectionProperty $reflectionProperty,
        $annotationName,
        $code = 0,
        \Exception $previous = null
    ) {
        $this->reflectionProperty = $reflectionProperty;
        $this->annotationName = $annotationName;

        $propertyName = $reflectionProperty->getName();
        $className = $reflectionProperty->getDeclaringClass()->getName();
        $message = "Annotation \"$annotationName\" not found on property \"$propertyName\" in class \"$className\".";
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return \ReflectionProperty
     */
    public function getReflectionProperty()
    {
        return $this->reflectionProperty;
    }

    /**
     * @return string
     */
    public function getAnnotationName()
    {
        return $this->annotationName;
    }

}

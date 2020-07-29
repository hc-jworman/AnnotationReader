<?php

/**
 * PropertyAnnotationFactory.php
 * @author Jack Worman
 */

namespace JWorman\AnnotationReader;

/**
 * Class PropertyAnnotationFactory
 * @package JWorman\AnnotationReader
 */
class PropertyAnnotationFactory
{
    /**
     * @var string
     */
    private $className;

    /**
     * @var string[]
     */
    private $propertyAnnotations;

    /**
     * PropertyAnnotationFactory constructor.
     * @param \ReflectionClass $reflectionClass
     * @throws Exceptions\AnnotationReaderException
     */
    public function __construct(\ReflectionClass $reflectionClass)
    {
        $this->className = $reflectionClass->getName();
        $this->propertyAnnotations = AnnotationReader::getClassMetaData($reflectionClass);
    }

    /**
     * @param string $propertyName
     * @param string $annotationName
     * @return AbstractAnnotation
     */
    public function getAnnotation($propertyName, $annotationName)
    {
        if (!isset($this->propertyAnnotations[$propertyName])) {
            throw new \InvalidArgumentException("Property '$propertyName' does not exist in '$this->className'.");
        }
        $annotations = $this->propertyAnnotations[$propertyName];
        if (!isset($annotations[$annotationName])) {
            throw new \InvalidArgumentException(
                "Annotation '$annotationName' does not exist on '$propertyName' in '$this->className'."
            );
        }
        $annotation = new $annotationName($annotations[$annotationName]);
        if (!($annotation instanceof AbstractAnnotation)) {
            throw new \LogicException(
                "Annotation '$annotationName' on '$propertyName' in '$this->className' does not extend AbstractAnnotation."
            );
        }
        return $annotation;
    }
}
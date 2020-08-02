<?php

/**
 * AnnotationFactory.php
 * @author Jack Worman
 */

namespace JWorman\AnnotationReader;

use JWorman\AnnotationReader\Exceptions\NotAnAnnotation;

/**
 * Class AnnotationFactory
 * @package JWorman\AnnotationReader
 */
class AnnotationFactory
{
    /**
     * @param array<string, string> $annotationData annotationName => jsonValue
     * @return AbstractAnnotation[]
     * @throws \ReflectionException if $annotationName does not exist
     */
    public static function batchCreate(array $annotationData)
    {
        $annotations = array();
        foreach ($annotationData as $annotationName => $jsonValue) {
            $annotation = self::create($annotationName, $jsonValue);
            $annotations[] = $annotation;
        }
        return $annotations;
    }

    /**
     * @param string $annotationName
     * @param string $jsonValue
     * @return AbstractAnnotation
     * @throws \ReflectionException if $annotationName does not exist
     */
    public static function create($annotationName, $jsonValue)
    {
        $reflectionClass = new \ReflectionClass($annotationName);
        if (!$reflectionClass->isSubclassOf(AbstractAnnotation::CLASS_NAME)) {
            throw new NotAnAnnotation($annotationName);
        }
        /** @var AbstractAnnotation $annotation */
        $annotation = $reflectionClass->newInstance($jsonValue);
        return $annotation;
    }
}
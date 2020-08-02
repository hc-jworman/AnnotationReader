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
     */
    public static function create($annotationName, $jsonValue)
    {
        try {
            $reflectionClass = new \ReflectionClass($annotationName);
        } catch (\ReflectionException $e) {
            throw new \InvalidArgumentException($e->getMessage(), 0, $e);
        }
        if (!$reflectionClass->isSubclassOf(AbstractAnnotation::CLASS_NAME)) {
            throw new NotAnAnnotation($annotationName);
        }
        /** @var AbstractAnnotation $annotation */
        $annotation = $reflectionClass->newInstance($jsonValue);
        return $annotation;
    }
}
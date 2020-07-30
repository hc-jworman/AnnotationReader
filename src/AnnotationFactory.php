<?php

/**
 * AnnotationFactory.php
 * @author Jack Worman
 */

namespace JWorman\AnnotationReader;

/**
 * Class AnnotationFactory
 * @package JWorman\AnnotationReader
 */
class AnnotationFactory
{
    /**
     * @param string[] $annotationData annotationName => jsonValue
     * @return AbstractAnnotation[]
     */
    public static function batchCreate(array $annotationData)
    {
        $annotations = array();
        foreach ($annotationData as $annotationName => $jsonValue) {
            $annotation = self::create($annotationName, $jsonValue);
            if ($annotation !== null) {
                $annotations[] = $annotation;
            }
        }
        return $annotations;
    }

    /**
     * @param string $annotationName
     * @param string $jsonValue
     * @return AbstractAnnotation|null
     */
    public static function create($annotationName, $jsonValue)
    {
        if (class_exists($annotationName)) {
            $annotation = new $annotationName($jsonValue);
            if ($annotation instanceof AbstractAnnotation) {
                return $annotation;
            }
        }
        return null;
    }
}
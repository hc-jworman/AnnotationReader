<?php

/**
 * AnnotationReader.php
 * @author Jack Worman
 */

namespace App;

use App\Exceptions\AnnotationReaderException;
use App\Exceptions\AnnotationSyntaxException;
use App\Exceptions\NoAnnotationException;
use App\Exceptions\NoDocCommentException;

/**
 * Class AnnotationReader
 * @package App
 */
class AnnotationReader
{
    const CLASS_NAME = __CLASS__;
    /* private */const ANNOTATION_START_PATTERN = '* @%s("';
    /* private */const ANNOTATION_END_PATTERN = '")';

    /**
     * @param \ReflectionProperty $reflectionProperty
     * @param string $annotationName
     * @return string
     * @throws AnnotationReaderException
     */
    public static function getPropertyAnnotation($reflectionProperty, $annotationName)
    {
        $docComment = $reflectionProperty->getDocComment();
        if ($docComment === false) {
            throw new NoDocCommentException($reflectionProperty);
        }

        $importAliases = self::getImportAliases($reflectionProperty->getDeclaringClass());
        $namePatterns = self::getNamePatterns($annotationName, $importAliases);

        $start = self::getStartPositionOfAnnotation($docComment, $namePatterns);
        if ($start === false) {
            throw new NoAnnotationException($reflectionProperty, $annotationName);
        }

        $end = strpos($docComment, self::ANNOTATION_END_PATTERN, $start);
        if ($end === false) {
            throw new AnnotationSyntaxException($reflectionProperty, $annotationName);
        }

        $value = substr($docComment, $start, $end - $start);
        return new $annotationName($value);
    }

    /**
     * Gets the starting position of the annotation within the doc comment.
     *
     * @param string $docComment
     * @param string[] $namePatterns
     * @return false|int
     */
    private static function getStartPositionOfAnnotation($docComment, $namePatterns)
    {
        foreach ($namePatterns as $namePattern) {
            $start = false;
            $matcher = '';
            foreach (self::getNamespaceSubsets($namePattern) as $namespaceSubset) {
                $matcher = sprintf(self::ANNOTATION_START_PATTERN, $namespaceSubset);
                $start = strpos($docComment, $matcher);
                if ($start !== false) {
                    break;
                }
            }
            if ($start === false) {
                continue;
            }
            return $start + strlen($matcher);
        }
        return false;
    }

    /**
     * @param string $fullyQualifiedName
     * @return string[]
     */
    private static function getNamespaceSubsets($fullyQualifiedName)
    {
        $fullyQualifiedNameParts = explode('\\', $fullyQualifiedName);
        $namespaceSubsets = array();
        for ($i = 0; $i < count($fullyQualifiedNameParts); $i++) {
            $namespaceSubsets[] = implode('\\', array_slice($fullyQualifiedNameParts, $i));
        }
        return $namespaceSubsets;
    }

    /**
     * @todo This does not consider the namespace of the file. If the class you are searching for is in the same
     *       directory, this function will not consider that.
     * @todo Does not support combined use statements.
     *
     * Example:
     *     use This/Is/A/Namespace;                       // 'Namespace'     => 'This/Is/A/Namespace'
     *     use This/Is/Also/A/Namespace as ThisIsAnAlias; // 'ThisIsAnAlias' => 'This/Is/Also/A/Namespace'
     *
     * @param \ReflectionClass $reflectionClass
     * @return string[]
     */
    private static function getImportAliases($reflectionClass)
    {
        $useStatements = array();

        $currentUseStatement = '';
        $currentAlias = null;

        $inUseStatement = false;
        $inAliasStatement = false;

        $fileContent = file_get_contents($reflectionClass->getFileName());
        $fileContent = preg_replace('/\s+/', ' ', $fileContent);
        $tokens = token_get_all($fileContent);
        foreach ($tokens as $token) {
            if (!isset($token[1])) {
                if ($inUseStatement && $token === ';') {
                    $inUseStatement = false;
                    $inAliasStatement = false;
                    if ($currentAlias === null) {
                        $namespaceParts = explode('\\', $currentUseStatement);
                        $currentAlias = $namespaceParts[count($namespaceParts) - 1];
                    }
                    $useStatements[$currentAlias] = $currentUseStatement;
                }
            } elseif ($inUseStatement) {
                if ($token[1] === 'as') {
                    $inAliasStatement = true;
                } elseif ($token[1] !== ' ') {
                    if ($inAliasStatement) {
                        $currentAlias = $token[1];
                    } else {
                        $currentUseStatement .= $token[1];
                    }
                }
            } elseif ($token[1] === 'use') {
                $inUseStatement = true;
                $currentUseStatement = '';
                $currentAlias = null;
            }
        }

        return $useStatements;
    }

    /**
     * Calculates all the potential patterns a class can be expressed as, given import aliases.
     *
     * @param string $annotationName
     * @param string[] $importAliases
     * @return string[]
     */
    private static function getNamePatterns($annotationName, $importAliases)
    {
        $namePatterns = array();
        $annotationNamespaceParts = explode('\\', $annotationName);
        foreach ($importAliases as $alias => $namespace) {
            $matchesAnnotation = true;
            $namespaceParts = explode('\\', $namespace);
            for ($i = 0; $i < count($namespaceParts); $i++) {
                if ($namespaceParts[$i] !== $annotationNamespaceParts[$i]) {
                    $matchesAnnotation = false;
                    break;
                }
            }
            if ($matchesAnnotation) {
                for (; $i < count($annotationNamespaceParts); $i++) {
                    $alias .= '\\' . $annotationNamespaceParts[$i];
                }
                $namePatterns[] = $alias;
            }
        }
        return $namePatterns;
    }
}

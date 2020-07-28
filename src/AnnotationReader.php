<?php

/**
 * AnnotationReader.php
 * @author Jack Worman
 */

namespace JWorman\AnnotationReader;

use JWorman\AnnotationReader\Exceptions\AnnotationReaderException;

/**
 * Class AnnotationReader
 * @package JWorman\AnnotationReader
 */
class AnnotationReader
{
    const CLASS_NAME = __CLASS__;

    /**
     * @param \ReflectionClass $class
     * @param bool $useCache
     * @return array
     */
    public static function getClassMetaData(\ReflectionClass $class, $useCache = true)
    {
        $fileName = __DIR__ . '/Cache/' . str_replace('\\', '-', $class->getName()) . '.php';
        if ($useCache && file_exists($fileName)) {
            return json_decode(include $fileName, true);
        }

        $importAliases = self::getImportAliases($class);
        $propertyAnnotations = array();
        foreach ($class->getProperties() as $reflectionProperty) {
            $docComment = $reflectionProperty->getDocComment();
            if ($docComment === false) {
                $propertyAnnotations[$reflectionProperty->getName()] = array();
                continue;
            }
            preg_match_all('/@([\\\\\w]+)\((?:|(.*?[]"}]))\)/', $reflectionProperty->getDocComment(), $matches);
            $names = $matches[1];
            foreach ($names as &$name) {
                $nameParts = explode('\\', $name);
                if (isset($importAliases[$nameParts[0]])) {
                    $nameParts[0] = $importAliases[$nameParts[0]];
                }
                $name = implode('\\', $nameParts);
            }
            $values = $matches[2];

            $propertyAnnotations[$reflectionProperty->getName()] = array_combine($names, $values);
        }
        $metaData = str_replace('\\\\', '\\\\\\', json_encode($propertyAnnotations));
        $metaData = str_replace('\'', '\\\'', $metaData);
        file_put_contents($fileName, "<?php\nreturn '" . $metaData . "';\n");
        return $propertyAnnotations;
    }

    /**
     * @param \ReflectionProperty $reflectionProperty
     * @param string $annotationName
     * @return string
     * @throws \Exception
     */
    public static function getPropertyAnnotation(\ReflectionProperty $reflectionProperty, $annotationName)
    {
        $classMetaData = self::getClassMetaData($reflectionProperty->getDeclaringClass());
        $annotations = $classMetaData[$reflectionProperty->getName()];
        if (isset($annotations[$annotationName])) {
            return new $annotationName($annotations[$annotationName]);
        }
        throw new AnnotationReaderException(
            sprintf(
                'Annotation "%s" does not exist on property "%s" in class "%s" has invalid syntax.',
                $annotationName,
                $reflectionProperty->getName(),
                $reflectionProperty->getDeclaringClass()->getNamespaceName()
            )
        );
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
}

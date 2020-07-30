<?php

/**
 * FileParser.php
 * @author Jack Worman
 */

namespace JWorman\AnnotationReader;

/**
 * Class FileParser
 * @package JWorman\AnnotationReader
 */
class FileParser
{
    const CLASS_NAME = __CLASS__;

    /**
     * @var \ReflectionClass
     */
    private $reflectionClass;

    /**
     * @var string[]|null
     */
    private $classImports = null;

    /**
     * FileParser constructor.
     * @param \ReflectionClass $reflectionClass
     */
    public function __construct(\ReflectionClass $reflectionClass)
    {
        $this->reflectionClass = $reflectionClass;
    }

    /**
     * @param string $docComment
     * @return string[]
     */
    public function getAnnotationDataFromDocComment($docComment)
    {
        $classImports = $this->getClassImports();
        $classNamespace = $this->reflectionClass->getNamespaceName();

        /**
         * https://regex101.com/r/tW6pI4/1
         */
        preg_match_all('/@([\\\\\w]+)\((?:|(.*?[]"}]))\)/', $docComment, $matches);
        $names = $matches[1];
        // Replacing aliases with their FQN
        foreach ($names as &$name) {
            $nameParts = explode('\\', $name);
            if (isset($classImports[$nameParts[0]])) {
                // Has an import statement:
                $nameParts[0] = $classImports[$nameParts[0]];
            } else {
                // Either invalid or in the same namespace:
                $nameParts[0] = $classNamespace . '\\' . $nameParts[0];
            }
            $name = implode('\\', $nameParts);
        }
        $jsonValues = $matches[2];
        return array_combine($names, $jsonValues);
    }

    /**
     * @return string[] Alias => FQN
     * @todo Does not support combined use statements.
     *
     */
    private function getClassImports()
    {
        if ($this->classImports !== null) {
            return $this->classImports;
        }

        $classImports = array();

        $currentUseStatement = '';
        $currentAlias = null;

        $inUseStatement = false;
        $inAliasStatement = false;

        $fileContent = file_get_contents($this->reflectionClass->getFileName());
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
                    $classImports[$currentAlias] = $currentUseStatement;
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
            } elseif ($token[1] === 'class') {
                break;
            }
        }

        $this->classImports = $classImports;
        return $classImports;
    }
}
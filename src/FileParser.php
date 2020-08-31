<?php

namespace JWorman\AnnotationReader;

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

        $annotationData = array();
        // Replacing aliases with their FQN
        foreach ($matches[1] as $index => $name) {
            $nameParts = explode('\\', $name);

            if (isset($classImports[$nameParts[0]])) { // Has an import statement:
                $nameParts[0] = $classImports[$nameParts[0]];
            } elseif (class_exists($name)) { // Is a FQN
                continue;
            } else { // In the same namespace or invalid:
                $nameParts[0] = $classNamespace . '\\' . $nameParts[0];
            }

            $name = implode('\\', $nameParts);

            if (is_subclass_of($name, AbstractAnnotation::CLASS_NAME)) {
                $annotationData[$name] = $matches[2][$index];
            }
        }
        return $annotationData;
    }

    /**
     * @return string[] Alias => FQN
     */
    private function getClassImports()
    {
        if ($this->classImports !== null) {
            return $this->classImports;
        }

        $classImports = array();

        $currentUseStatement = '';
        $currentAlias = '';
        $currentGroupStatement = '';
        $inUseStatement = false;
        $inAliasStatement = false;
        $inGroupStatement = false;
        $depth = 0;

        $fileName = $this->reflectionClass->getFileName();
        if ($fileName === false) {
            throw new \InvalidArgumentException('Cannot get file name of an internal class.');
        }
        $fileContent = file_get_contents($fileName);
        if ($fileContent === false) {
            throw new \RuntimeException('Could not get contents from file, "' . $fileName . '".');
        }

        $tokens = token_get_all($fileContent);
        foreach ($tokens as $token) {
            if (is_array($token)) {
                if ($inUseStatement) {
                    switch ($token[0]) {
                        case T_AS:
                            $inAliasStatement = true;
                            break;
                        case T_STRING:
                            $currentAlias = $token[1];
                            // fallthrough
                        case T_NS_SEPARATOR:
                            if (!$inAliasStatement) {
                                $currentUseStatement .= $token[1];
                            }
                            break;
                        case T_FUNCTION:
                        case T_CONST:
                            $inUseStatement = false;
                            $currentUseStatement = '';
                            $inAliasStatement = false;
                            $currentAlias = null;
                            $inGroupStatement = false;
                            break;
                    }
                } else {
                    switch ($token[0]) {
                        case T_USE:
                            if ($depth === 0) {
                                $inUseStatement = true;
                            }
                            break;
                    }
                }
            } elseif ($inUseStatement) {
                switch ($token) {
                    case ';':
                        $classImports[$currentAlias]
                            = ($inGroupStatement ? $currentGroupStatement : '') . $currentUseStatement;

                        $inUseStatement = false;
                        $currentUseStatement = '';
                        $inAliasStatement = false;
                        $currentAlias = null;
                        $inGroupStatement = false;
                        break;
                    case ',':
                        $classImports[$currentAlias]
                            = ($inGroupStatement ? $currentGroupStatement : '') . $currentUseStatement;

                        $currentUseStatement = '';
                        $inAliasStatement = false;
                        $currentAlias = null;
                        break;
                    case '{':
                        $depth++;
                        $inGroupStatement = true;
                        $currentGroupStatement = $currentUseStatement;
                        $currentUseStatement = '';
                        break;
                    case '}':
                        $depth--;
                        break;
                }
            } else {
                switch ($token) {
                    case '{':
                        $depth++;
                        break;
                    case '}':
                        $depth--;
                        break;
                }
            }
        }

        $this->classImports = $classImports;
        return $classImports;
    }
}

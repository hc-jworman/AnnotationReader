<?php

namespace JWorman\AnnotationReader;

use JWorman\AnnotationReader\Exceptions\PropertyDoesNotExist;

abstract class AbstractAnnotation
{
    const CLASS_NAME = __CLASS__;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * AbstractAnnotation constructor.
     * @param string $jsonValue
     */
    final public function __construct($jsonValue)
    {
        $this->value = json_decode($jsonValue);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException("Invalid JSON in annotation: $jsonValue");
        }
        if (is_object($this->value)) {
            $this->mapObjectValuesToProperties();
        }
    }

    /**
     * @return void
     */
    private function mapObjectValuesToProperties()
    {
        foreach ($this->value as $propertyName => $propertyValue) {
            try {
                $reflectionProperty = new \ReflectionProperty($this, $propertyName);
                $reflectionProperty->setAccessible(true);
                $reflectionProperty->setValue($this, $propertyValue);
            } catch (\ReflectionException $e) {
                throw new PropertyDoesNotExist(__CLASS__, $propertyName, 0, $e);
            }
        }
    }

    /**
     * @return mixed
     */
    final public function getValue()
    {
        return $this->value;
    }
}

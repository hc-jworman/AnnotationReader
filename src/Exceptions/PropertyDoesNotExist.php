<?php

namespace JWorman\AnnotationReader\Exceptions;

class PropertyDoesNotExist extends \DomainException
{
    const CLASS_NAME = __CLASS__;
    /**
     * @var string
     */
    private $annotationName;

    /**
     * @var string
     */
    private $propertyName;

    /**
     * PropertyDoesNotExist constructor.
     * @param string $annotationName
     * @param string $propertyName
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct($annotationName, $propertyName, $code = 0, \Exception $previous = null)
    {
        $this->annotationName = $annotationName;
        $this->propertyName = $propertyName;

        $message = "Property \"$propertyName\" does not exist in \"$annotationName\".";
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getAnnotationName()
    {
        return $this->annotationName;
    }

    /**
     * @return string
     */
    public function getPropertyName()
    {
        return $this->propertyName;
    }
}

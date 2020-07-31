# AnnotationReader
Install:
```text
composer require jworman/annotation-reader
```

Example:
```php
use JWorman\AnnotationReader\AbstractAnnotation;

class MyAnnotation extends AbstractAnnotation
{
}
```
```php
use MyAnnotation;

class Example
{
    /**
     * @MyAnnotation("fizzbuzz")
     */
    private $id;
}
```
```php
use JWorman\AnnotationReader\AnnotationReader;

$annotationReader = new AnnotationReader();
$reflectionProperty = new \ReflectionProperty('Example', 'id');
$annotation = $annotationReader->getPropertyAnnotation($reflectionProperty, 'MyAnnotation');
$value = $annotation->getValue(); // Returns "fizzbuzz"
```
Annotations can have any JSON value inside them.
```php
/**
  * @MyAnnotation("fizzbuzz")
  * @AnotherOne({"isCool": true, "list": [null, false, {"nested": "object"}]})
  */
```
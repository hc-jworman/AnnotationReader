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
Annotations can have any valid JSON value inside them.
```php
/**
  * @MyAnnotation("fizzbuzz")
  * @AnotherOne({"isCool": true, "list": [null, false, {"nested": "object"}]})
  */
```
Annotations that define objects in their JSON will have their properties mapped to from the JSON.
```php
use JWorman\AnnotationReader\AbstractAnnotation;

class AnotherOne extends AbstractAnnotation
{
    private $isCool; // From above annotations will equal: true
    private $list;   // From above annotations will equal: [null, false, \stdObject()]
}
```
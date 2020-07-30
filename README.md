# AnnotationReader
Allows the parsing of annotations within doc comments.

```
composer require jworman/annotation-reader
```

```php
$annotationReader = new AnnotationReader();
$annotation = $annotationReader->getPropertyAnnotation('propertyName', 'annotationName');
```

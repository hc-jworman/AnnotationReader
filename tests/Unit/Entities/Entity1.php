<?php

/**
 * Entity1.php
 * @author Jack Worman
 */

namespace JWorman\AnnotationReader\Tests\Unit\Entities;

use JWorman\AnnotationReader\Tests\Unit\Annotations\Annotation1;
use JWorman\AnnotationReader\Tests\Unit as Blah;

/**
 * Class Entity1
 * @package JWorman\AnnotationReader\Tests\Unit\Entities
 */
class Entity1
{
    const CLASS_NAME = __CLASS__;

    /**
     * @Annotation1("fizzbuzz")
     * @Blah This is only here so 'Blah' is used.
     */
    private $property1;
}

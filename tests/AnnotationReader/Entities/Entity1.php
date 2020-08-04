<?php

/**
 * Entity1.php
 * @author Jack Worman
 */

namespace JWorman\AnnotationReader\Tests\AnnotationReader\Entities;

use JWorman\AnnotationReader\Tests\AnnotationReader as Blah;
use JWorman\AnnotationReader\Tests\AnnotationReader\Annotations\Annotation1;

/**
 * Class Entity1
 * @package JWorman\AnnotationReader\Tests\Unit\Entities
 */
class Entity1
{
    const CLASS_NAME = __CLASS__;

    /**
     * @Annotation1("fizzbuzz")
     * @Blah() This is only here so 'Blah' is used.
     */
    private $property1;

    /**
     * @var string
     * @Ann1() @Ann2("var1")
     * @Ann3("var1") @Ann4("var1")
     * @Ann5("var1") @Ann6("var1") @Ann7("var1")
     * @Ann8("name@name.com") @Ann9("name()")
     * @Ann10\Name("var1", "var2")
     * @Ann11(["var1", "var2"], "var3")
     * @Ann12\Filter\Name(["var1", "var2"], "var3", {"var4": "var5"})
     * @Ann13("name() ", "aaa")'
     */
    private $property2;
}

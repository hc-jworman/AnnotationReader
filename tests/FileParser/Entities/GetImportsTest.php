<?php

/**
 * GetImportsTest.php
 * @author Jack Worman
 */

namespace JWorman\AnnotationReader\Tests\FileParser\Entities;

use A\Fully\Qualified\Name;
use Has\An\Alias as ThisIsAnAlias;
use First\Multiple as First, Second\Multiple;
use Third, Fourth\Multiple as Fourth;
use Group\Names\{ClassA, ClassB as B, ClassC as C};

use function blah;
use const blah2;

trait ThisIsATrait
{
}

/**
 * Class GetImportsTest
 * @package JWorman\AnnotationReader\Tests\Unit\Entities
 */
class GetImportsTest
{
    use ThisIsATrait;

    const CLASS_NAME = __CLASS__;
}

use At\The\Bottom;

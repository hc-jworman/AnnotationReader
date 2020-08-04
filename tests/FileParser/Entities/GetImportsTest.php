<?php

/**
 * GetImportsTest.php
 * @author Jack Worman
 */

namespace JWorman\AnnotationReader\Tests\FileParser\Entities;

use A\Fully\Qualified\Name;
use At\The\Bottom;
use Group\Names\{ClassA};
use Second\Multiple;
use Third;

use function blah;

use const blah2;

trait ThisIsATrait {}

/**
 * Class GetImportsTest
 * @package JWorman\AnnotationReader\Tests\Unit\Entities
 */
class GetImportsTest
{
    use ThisIsATrait;

    const CLASS_NAME = __CLASS__;
}

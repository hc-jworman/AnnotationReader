<?php

/**
 * Entity1.php
 * @author Jack Worman
 */

namespace App\Tests\Unit\Entities;

use App\Tests\Unit\Annotations\Annotation1;
use App\Tests\Unit as Blah;

/**
 * Class Entity1
 * @package App\Tests\Unit\Entities
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

<?php

/**
 * Annotation1.php
 * @author Jack Worman
 */

namespace App\Tests\Unit\Annotations;

use App\AbstractAnnotation;

/**
 * Class Annotation1
 * @package App\Tests\Unit\Annotations
 */
class Annotation1 extends AbstractAnnotation
{
    const CLASS_NAME = __CLASS__;

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}

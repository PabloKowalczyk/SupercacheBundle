<?php

declare(strict_types=1);

namespace PabloK\SupercacheBundle\Exceptions;

/**
 * Thrown while pattern used for locating certain element(s) cannot be found and operation cannot be completed.
 */
class PatternNotFoundException extends \LogicException
{
}

<?php

declare(strict_types=1);

namespace PabloK\SupercacheBundle\Exceptions;

/**
 * Thrown when requested filesystem patch cannot be found.
 */
class PathNotFoundException extends FilesystemException
{
    /**
     * @param string $path filename or path which cannot be found
     */
    public function __construct(string $path, string $details = '')
    {
        $message = 'Failed to locate filesystem path ' . $path;

        if (!empty($details)) {
            $message .= ' - ' . $details . '.';
        }

        parent::__construct($message);
    }
}

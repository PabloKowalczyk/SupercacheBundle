<?php

declare(strict_types=1);

namespace PabloK\SupercacheBundle\Cache;

use PabloK\SupercacheBundle\Exceptions\SecurityViolationException;

class CacheElement
{
    /** @deprecated Will be moved to CacheType */
    const TYPE_HTML = CacheType::TYPE_HTML;

    /** @deprecated Will be moved to CacheType */
    const TYPE_JAVASCRIPT = CacheType::TYPE_JAVASCRIPT;

    /** @deprecated Will be moved to CacheType */
    const TYPE_BINARY = CacheType::TYPE_BINARY;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $content;

    /**
     * @param string $path    Cache path, eg. /sandbox
     * @param string $content Content to cache, eg. HTML
     * @param string $type    Any valid type defined by self::TYPE_* constants
     *
     * @throws SecurityViolationException Specified cache path was found to be dangerous (eg. /../../sandbox)
     */
    public function __construct(
        string $path,
        string $content,
        string $type = self::TYPE_BINARY
    ) {
        $this->setPath($path);
        $this->updateContent($content);
        $this->setType($type);
    }

    /**
     * Provides cached element path.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Provides cached element type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Provides cache element contents.
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Sets element content.
     *
     * @param string $content
     */
    public function updateContent(string $content)
    {
        $this->content = $content;
    }

    /**
     * Sets cached element path.
     *
     * @param string $path Cache path, eg. /sandbox
     */
    private function setPath(string $path)
    {
        $this->path = \urldecode($path);
    }

    /**
     * Sets element type.
     *
     * @param string $type Any valid type defined by self::TYPE_* constants
     *
     * @throws \InvalidArgumentException
     */
    private function setType(string $type)
    {
        if (!\in_array($type, CacheType::TYPES, true)) {
            throw new \InvalidArgumentException('Invalid type specified');
        }

        $this->type = $type;
    }
}

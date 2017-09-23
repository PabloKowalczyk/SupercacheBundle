<?php

namespace PabloK\SupercacheBundle\Cache;

use PabloK\SupercacheBundle\Exceptions\SecurityViolationException;

class CacheElement
{
    /** @deprecated Will be moved to CacheType */
    const TYPE_HTML = 'html';

    /** @deprecated Will be moved to CacheType */
    const TYPE_JAVASCRIPT = 'js';

    /** @deprecated Will be moved to CacheType */
    const TYPE_BINARY = 'bin';

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
     * @param string $path Cache path, eg. /sandbox
     * @param string $content Content to cache, eg. HTML
     * @param string $type Any valid type defined by self::TYPE_* constants
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
     * Sets cached element path
     *
     * @param string $path Cache path, eg. /sandbox
     */
    private function setPath(string $path)
    {
        $this->path = urldecode($path);
    }

    /**
     * Sets cached element path without url decoding
     *
     * @param string $path Cache path, eg. /sandbox
     */
    private function setRawPath(string $path)
    {
        $this->path = $path;
    }

    /**
     * Provides cached element path
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Sets element type
     *
     * @param string $type Any valid type defined by self::TYPE_* constants
     *
     * @throws \InvalidArgumentException
     */
    private function setType(string $type): void
    {
        if ($type !== self::TYPE_HTML && $type !== self::TYPE_JAVASCRIPT && $type !== self::TYPE_BINARY) {
            throw new \InvalidArgumentException('Invalid type specified');
        }

        $this->type = $type;
    }

    /**
     * Provides cached element type
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Provides cache element contents
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Sets element content
     *
     * @param string $content
     */
    public function updateContent(string $content): void
    {
        $this->content = $content;
    }
}

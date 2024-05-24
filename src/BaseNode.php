<?php

namespace  Edu\IU\RSB\StructuredDataNodes;

class BaseNode{

    public ?string $type = null;
    public ?string $identifier = null;
    public ?array $structuredDataNodes = null;
    public ?string $text = null;
    public ?string $assetType = null;
    public ?string $blockId = null;
    public ?string $blockPath = null;
    public ?string $fileId = null;
    public ?string $filePath = null;
    public ?string $pageId = null;
    public ?string $pagePath = null;
    public ?string $symlinkId = null;
    public ?string $symlinkPath = null;
    public bool $recycled;

    public function __construct(string $type, string $identifier)
    {
        $this->type = $type;
        $this->identifier = $identifier;
    }

    public function getNodeArray():array
    {
        return get_object_vars($this);
    }

    public function setValue(string $key, string | bool | array $value):void
    {
        if ($key == 'type'){
            throw new \RuntimeException("$key cannot be modified");
        }
        $this->{$key} = $value;
    }

}
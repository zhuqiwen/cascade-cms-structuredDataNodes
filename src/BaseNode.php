<?php

namespace  Edu\IU\RSB\StructuredDataNodes;

class BaseNode{
    public array $nodeArray = [
        'type' => null,
        'identifier' => null,
        'structuredDataNodes' => null,
        'text' => null,
        'assetType' => null,
        'blockId' => null,
        'blockPath' => null,
        'fileId' => null,
        'filePath' => null,
        'pageId' => null,
        'pagePath' => null,
        'symlinkId' => null,
        'symlinkPath' => null,
        'recycled' => false,
    ];

    public function __construct(string $type, string $identifier)
    {
        $this->nodeArray['type'] = $type;
        $this->nodeArray['identifier'] = $identifier;
    }

    public function getNodeArray():array
    {
        return $this->nodeArray;
    }
}
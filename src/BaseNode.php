<?php

namespace  Edu\IU\RSB\StructuredDataNodes;

class BaseNode implements NodeInterface {
    /**
     * DO NOT add any public attributes
     * since the following attributes are returned as the keys in the node's associative array
     */
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
    public bool $recycled = false;

    /**
     * If any extra attributes are needed, add only as private, protected, and/or static below here
     */
    private string $pathNoPosition = 'path without position NOT SET';
    private string $pathWithPosition = 'path with position NOT SET';

    /**
     * END of non-public attributes
     */



    public function __construct(string $type, string $identifier)
    {
        $this->type = $type;
        $this->identifier = $identifier;
    }

    public function setPathNoPosition(string $parentPathNoPosition = ''):void
    {
        $this->pathNoPosition = $parentPathNoPosition . DIRECTORY_SEPARATOR . $this->identifier;
    }

    public function setPathWithPosition(string $parentPath, int $currentNodePosition):void
    {
        $this->pathWithPosition = $parentPath . DIRECTORY_SEPARATOR . $this->identifier . '[' . $currentNodePosition . ']';
        //remove position info and construct path without position
        $parentPathWithoutPosition = preg_replace('/\[[0-9]\]?/', '', $parentPath);
        $this->setPathNoPosition($parentPathWithoutPosition);
    }


    /**
     * the returned array will be used to construct data for creating/updating pages and blocks in Cascade CMS
     * @return array
     */
    public function getNodeArray():array
    {
        // use first class callable to get only public attributes and their values as associative array
        return get_object_vars(...)->__invoke($this);
    }

    /**
     * @param string $key
     * @param string|bool|array $value
     * @return void
     */
    public function setValue(string $key, string | bool | array | null $value):void
    {
        if ($key == 'type'){
            throw new \RuntimeException("$key cannot be modified");
        }
        if (!in_array($key, array_keys($this->getNodeArray()))){
            throw new \RuntimeException("$key is not a valid attribute.");
        }
        $this->{$key} = $value;
    }

    public function getPathWithPosition():string
    {
        return trim($this->pathWithPosition, DIRECTORY_SEPARATOR);
    }

    public function getPathNoPosition():string
    {
        return trim($this->pathNoPosition, DIRECTORY_SEPARATOR);
    }

    //TODO: add shortcut methods for setValue()
    public function setValueIdentifier(string $val):void
    {
        $this->identifier = $val;
    }

    public function setValueRecycled(bool $val):void
    {
        $this->recycled = $val;
    }

}
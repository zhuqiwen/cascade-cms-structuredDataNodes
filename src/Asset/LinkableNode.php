<?php

namespace  Edu\IU\RSB\StructuredDataNodes\Asset;

class LinkableNode extends AssetNode {

    public function __construct(string $identifier, string $assetId = '', string $assetPath = '', string $whichType = '')
    {
        parent::__construct($identifier, 'page,file,symlink');

        if (!empty(trim($whichType))){
            $this->setValues($whichType, $assetId, $assetPath);
        }


    }

    public function setValues(string $whichType, string $assetId, string $assetPath):void
    {
        if(!in_array($whichType, ['page', 'file', 'symlink'])){
            throw new \RuntimeException('last parameter $whichType must be "page", "file", or "symlink"');
        }

        $idKey = $whichType . 'Id';
        $pathKey = $whichType . 'Path';

        $this->{$idKey} = $assetId;
        $this->{$pathKey} = $assetPath;
    }


}
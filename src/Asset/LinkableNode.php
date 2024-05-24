<?php

namespace  Edu\IU\RSB\StructuredDataNodes\Asset;

class LinkableNode extends AssetNode {

    public function __construct(string $identifier, string $assetId, string $assetPath, string $whichType)
    {
        if(!in_array($whichType, ['page', 'file', 'symlink'])){
            throw new \RuntimeException('last parameter $whichType must be "page", "file", or "symlink"');
        }
        parent::__construct($identifier, 'page,file,symlink');

        $idKey = $whichType . 'Id';
        $pathKey = $whichType . 'Path';

        $this->{$idKey} = $assetId;
        $this->{$pathKey} = $assetPath;
    }
}
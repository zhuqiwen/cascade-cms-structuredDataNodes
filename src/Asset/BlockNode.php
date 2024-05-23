<?php

namespace  Edu\IU\RSB\StructuredDataNodes\Asset;

class BlockNode extends AssetNode {

    public function __construct(string $identifier, string $blockId, string $blockPath)
    {

        parent::__construct($identifier);

        $this->nodeArray['blockId'] = $blockId;
        $this->nodeArray['blockPath'] = $blockPath;
        $this->nodeArray['assetType'] = 'block';

    }
}
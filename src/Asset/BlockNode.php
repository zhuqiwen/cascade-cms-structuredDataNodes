<?php

namespace  Edu\IU\RSB\StructuredDataNodes\Asset;

use Edu\IU\RSB\StructuredDataNodes\NodeInterface;

class BlockNode extends AssetNode implements NodeInterface {

    public function __construct(string $identifier, string | null $blockId = null, string | null $blockPath = null)
    {

        parent::__construct($identifier, 'block');
        $this->blockId = $blockId;
        $this->blockPath = $blockPath;
    }

    public function setValueBlockPath(string $blockPath):void
    {
        $this->blockPath = $blockPath;
    }

    public function setValueBlockId(string $blockId):void
    {
        $this->blockId = $blockId;
    }
}
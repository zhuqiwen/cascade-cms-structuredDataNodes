<?php

namespace  Edu\IU\RSB\StructuredDataNodes\Asset;

class BlockNode extends AssetNode {

    public function __construct(string $identifier, string $blockId = '', string $blockPath = '')
    {

        parent::__construct($identifier, 'block');
        $this->blockId = $blockId;
        $this->blockPath = $blockPath;
    }
}
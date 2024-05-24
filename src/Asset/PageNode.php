<?php

namespace  Edu\IU\RSB\StructuredDataNodes\Asset;

class PageNode extends AssetNode {

    public function __construct(string $identifier, string $assetId, string $assetPath)
    {
        parent::__construct($identifier, 'page');
        $this->pageId = $assetId;
        $this->pagePath = $assetPath;
    }
}
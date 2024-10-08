<?php

namespace  Edu\IU\RSB\StructuredDataNodes\Asset;

class PageNode extends AssetNode {

    public function __construct(string $identifier, string | null $assetId = null, string | null $assetPath = null)
    {
        parent::__construct($identifier, 'page');
        $this->pageId = $assetId;
        $this->pagePath = $assetPath;
    }

    public function setValuePagePath(string $pagePath):void
    {
        $this->pagePath = $pagePath;
    }

    public function setValueFileId(string $pageId):void
    {
        $this->pageId = $pageId;
    }
}
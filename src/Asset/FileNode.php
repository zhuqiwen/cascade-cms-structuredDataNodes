<?php

namespace  Edu\IU\RSB\StructuredDataNodes\Asset;

class FileNode extends AssetNode {

    public function __construct(string $identifier, string $assetId, string $assetPath)
    {
        parent::__construct($identifier);

        $this->nodeArray['fileId'] = $assetId;
        $this->nodeArray['filePath'] = $assetPath;
        $this->nodeArray['assetType'] = 'file';
    }
}
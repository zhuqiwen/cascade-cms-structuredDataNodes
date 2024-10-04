<?php

namespace  Edu\IU\RSB\StructuredDataNodes\Asset;

class FileNode extends AssetNode {

    public function __construct(string $identifier, string | null $assetId = null, string | null $assetPath = null)
    {
        parent::__construct($identifier, 'file');
        $this->fileId = $assetId;
        $this->filePath = $assetPath;
    }

    public function setValueFilePath(string $filePath):void
    {
        $this->filePath = $filePath;
    }

    public function setValueFileId(string $fileId):void
    {
        $this->fileId = $fileId;
    }
}
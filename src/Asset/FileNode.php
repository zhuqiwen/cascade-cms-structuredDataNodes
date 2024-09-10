<?php

namespace  Edu\IU\RSB\StructuredDataNodes\Asset;

class FileNode extends AssetNode {

    public function __construct(string $identifier, string $assetId = '', string $assetPath = '')
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
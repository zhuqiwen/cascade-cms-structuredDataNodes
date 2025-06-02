<?php

namespace  Edu\IU\RSB\StructuredDataNodes;

use Edu\IU\RSB\StructuredDataNodes\Asset\AssetNode;
use Edu\IU\RSB\StructuredDataNodes\Asset\BlockNode;
use Edu\IU\RSB\StructuredDataNodes\Asset\FileNode;
use Edu\IU\RSB\StructuredDataNodes\Asset\LinkableNode;
use Edu\IU\RSB\StructuredDataNodes\Asset\PageNode;
use Edu\IU\RSB\StructuredDataNodes\Asset\SymlinkNode;


/**
 * convert structured data nodes from page, or block into data node objects
 */
class Converter{

    public array $valueNodes = [];
    use NodeTraits;

    public function convert(array $originalNodesArray): array
    {
        $result = [];
        foreach ($originalNodesArray as $node){
            $result[] = $this->convertNode($node);
        }

        return $result;
    }

    public function convertNode(\stdClass $node): BaseNode
    {
        return match ($node->type){
            'asset' => $this->processAssetNode($node),
            'group' => $this->processGroupNode($node),
            'text' => $this->processTextNode($node),
        };
    }

    public function processAssetNode(\stdClass $assetNode): AssetNode
    {
        $assetInfo = $this->getAssetInfo($assetNode);
        //get assetid, asset path and asset type
        extract($assetInfo);
        return match ($assetNode->assetType){
            'page,file,symlink' => new LinkableNode($assetNode->identifier, $assetId, $assetPath, $type),
            'file' => new FileNode($assetNode->identifier, $assetId, $assetPath),
            'block' => new BlockNode($assetNode->identifier, $assetId, $assetPath),
            'page' => new PageNode($assetNode->identifier, $assetId, $assetPath),
            'symlink' => new SymlinkNode($assetNode->identifier, $assetId, $assetPath)
        };
    }

    public function processGroupNode(\stdClass $groupNode): GroupNode
    {
        $dataNode = new GroupNode($groupNode->identifier);

        $structuredDataNode = $groupNode->structuredDataNodes->structuredDataNode;
        $structuredDataNodeArray = $structuredDataNode;
        // in original structuredDataNode, if there is only one child, then it's a stdclass instead of array
        if (!is_array($structuredDataNode)){
            $structuredDataNodeArray = [$structuredDataNode];
        }

        foreach ($structuredDataNodeArray as $childNode) {
            $dataNode->addChild($this->convertNode($childNode));
        }

        return $dataNode;
    }

    public function processTextNode(\stdClass $textNode): BaseNode
    {
        $dataNode = new BaseNode('text', $textNode->identifier);
        $dataNode->setValue('text', $textNode->text);

        return $dataNode;
    }



    /**
     * converter that also constructs path info
     */
    public function convertWithPathInfo(array $originalNodesArray): array
    {
        $result = [];
        $counter = [];
        foreach ($originalNodesArray as $node){
            if (key_exists($node->identifier, $counter)){
                $counter[$node->identifier] = $counter[$node->identifier] + 1;
            }else{
                $counter[$node->identifier] = 0;
            }
            $result[] = $this->convertNodeWithPathInfo($node, '', $counter[$node->identifier]);
        }

        return $result;
    }

    public function collectValueNodes(BaseNode $convertedNode): void
    {
        if ($convertedNode->type === 'asset' || $convertedNode->type === 'text'){
            $this->valueNodes[] = $convertedNode;
        }
    }


    public function convertNodeWithPathInfo(\stdClass $node, string $parentPath, int $nodePosition): BaseNode
    {
        $convertedNode =  match ($node->type){
            'asset' => $this->processAssetNodeWithPathInfo($node, $parentPath, $nodePosition),
            'group' => $this->processGroupNodeWithPathInfo($node, $parentPath, $nodePosition),
            'text' => $this->processTextNodeWithPathInfo($node, $parentPath, $nodePosition),
        };

        $this->collectValueNodes($convertedNode);

        return $convertedNode;

    }
    public function processAssetNodeWithPathInfo(\stdClass $assetNode, string $parentPath, int $nodePosition): AssetNode
    {
        $assetInfo = $this->getAssetInfo($assetNode);
        //get assetid, asset path and asset type
        extract($assetInfo);

        $node = match ($assetNode->assetType){
            'page,file,symlink' => new LinkableNode($assetNode->identifier, $assetId, $assetPath, $type),
            'file' => new FileNode($assetNode->identifier, $assetId, $assetPath),
            'block' => new BlockNode($assetNode->identifier, $assetId, $assetPath),
            'page' => new PageNode($assetNode->identifier, $assetId, $assetPath),
            'symlink' => new SymlinkNode($assetNode->identifier, $assetId, $assetPath)
        };

        $node->setPathWithPosition($parentPath, $nodePosition);
        return $node;
    }
    public function processGroupNodeWithPathInfo(\stdClass $groupNode, string $parentPath,  int $nodePosition): GroupNode
    {
        $dataNode = new GroupNode($groupNode->identifier);

        $structuredDataNode = $groupNode->structuredDataNodes->structuredDataNode;
        $structuredDataNodeArray = $structuredDataNode;
        // in original structuredDataNode, if there is only one child, then it's a stdclass instead of array
        if (!is_array($structuredDataNode)){
            $structuredDataNodeArray = [$structuredDataNode];
        }

        $dataNode->setPathWithPosition($parentPath, $nodePosition);


        $counter = [];
        foreach ($structuredDataNodeArray as $index => $childNode) {
            if (key_exists($childNode->identifier, $counter)){
                $counter[$childNode->identifier] = $counter[$childNode->identifier] + 1;
            }else{
                $counter[$childNode->identifier] = 0;
            }
            $dataNode->addChild($this->convertNodeWithPathInfo($childNode, $dataNode->getPathWithPosition(), $counter[$childNode->identifier]));
        }



        return $dataNode;
    }
    public function processTextNodeWithPathInfo(\stdClass $textNode, string $parentPath, int $nodePosition): BaseNode
    {
        $dataNode = new BaseNode('text', $textNode->identifier);
        $dataNode->setValue('text', $textNode->text);
        $dataNode->setPathWithPosition($parentPath, $nodePosition);


        return $dataNode;
    }
}
<?php

namespace  Edu\IU\RSB\StructuredDataNodes;


class GroupNode extends BaseNode implements NodeInterface {

    private array $textChildrenDict = [];
    private array $assetChildrenDict = [];
    private array $groupChildrenDict = [];



    public function __construct(string $identifier, BaseNode $node = null)
    {
        parent::__construct('group', $identifier);
        $this->setValue('structuredDataNodes', ['structuredDataNode' => null]) ;
        if(!is_null($node)){
            $this->addChild($node);
        }

    }

    public function getTextChildrenDict(): array
    {
        return $this->textChildrenDict;
    }

    public function getAssetChildrenDict(): array
    {
        return $this->assetChildrenDict;
    }

    public function getGroupChildrenDict(): array
    {
        return $this->groupChildrenDict;
    }

    public function addChild(NodeInterface $node):void
    {
        // add to dicts
        $this->addToChildrenDict($node);
        $thisNodeArray = $this;
        if (is_null($thisNodeArray->structuredDataNodes['structuredDataNode'])){
            $this->setValue('structuredDataNodes', ['structuredDataNode' => $node]);
            // only 1
        }elseif(!array_is_list((array)$thisNodeArray->structuredDataNodes['structuredDataNode'])){
            $theOneChildNode = $thisNodeArray->structuredDataNodes['structuredDataNode'];
            $tmpArray = [
                'structuredDataNode' => [
                    $theOneChildNode,
                    $node
                ]
            ];
            $this->setValue('structuredDataNodes', $tmpArray);
            // multiple
        }else{
            $tmpArray = $thisNodeArray->structuredDataNodes;
            $tmpArray['structuredDataNode'][] = $node;
            $this->setValue('structuredDataNodes', $tmpArray);
        }
    }

    public function setValueStructuredDataNodes(BaseNode $node):void
    {
        $this->addChild($node);
    }


    /**
     * override the method so that it recursively convert each child into array
     * @return array
     */
    public function getNodeArray(): array
    {
        $result = get_object_vars(...)->__invoke($this);

        if (isset($result['structuredDataNodes']['structuredDataNode'])){
            $childrenArray = [];
            if (!is_array($result['structuredDataNodes']['structuredDataNode'])){
                $result['structuredDataNodes']['structuredDataNode'] = [$result['structuredDataNodes']['structuredDataNode']];
            }

            foreach ($result['structuredDataNodes']['structuredDataNode'] as $childNode) {
                $childrenArray[] = $childNode->getNodeArray();
            }

            $result['structuredDataNodes']['structuredDataNode'] = $childrenArray;

        }

        return $result;
    }


    public function getSingleChildNodeByPath(string $pathToNodeFromThisGroupNode): BaseNode | null
    {
        if (empty(trim($pathToNodeFromThisGroupNode))){
            return null;
        }
        //normalize path to be
        $pathToNodeFromThisGroupNode = trim($pathToNodeFromThisGroupNode, DIRECTORY_SEPARATOR);
        //try textChildrenDict first
        if (array_key_exists($pathToNodeFromThisGroupNode, $this->textChildrenDict)){
            return $this->textChildrenDict[$pathToNodeFromThisGroupNode];
            //then try assetChildrenDict
        }elseif (array_key_exists($pathToNodeFromThisGroupNode, $this->assetChildrenDict)){
            return $this->assetChildrenDict[$pathToNodeFromThisGroupNode];
        }else{ //if not found, then try going through child Group nodes
            $pathToUseInChildGroupNodes = $this->getPathToUseInChildGroupNode($pathToNodeFromThisGroupNode);

            foreach ($this->groupChildrenDict as $childGroupNode){
                $targetNode = $childGroupNode->getSingleChildNodeByPath($pathToUseInChildGroupNodes);
                if (!is_null($targetNode)){
                    // only find the first that matches
                    return $targetNode;
                }
            }
        }

        return null;
    }

    private function getPathToUseInChildGroupNode(string $pathToNodeFromThisGroupNode): string
    {
        $pathUsedInChildGroupNodes = explode(DIRECTORY_SEPARATOR, $pathToNodeFromThisGroupNode);
        //rm current node's identifier
        array_shift($pathUsedInChildGroupNodes);

        return implode(DIRECTORY_SEPARATOR, $pathUsedInChildGroupNodes);
    }

    private function addToChildrenDict(NodeInterface $node):void
    {
        match ($node->type){
            'text' => $this->addToTextChildrenDict($node),
            'asset' => $this->addToAssetChildrenDict($node),
            'group' => $this->addToGroupChildrenDict($node),
        };

    }

    private function addToTextChildrenDict(NodeInterface $node):void
    {
        $path = $this->constructPath($node);
        //node identifier is unique, so is $path
        $this->textChildrenDict[$path] = $node;
    }

    private function addToAssetChildrenDict(NodeInterface $node):void
    {
        $path = $this->constructPath($node);
        $this->assetChildrenDict[$path] = $node;
    }

    private function addToGroupChildrenDict(NodeInterface $node):void
    {
        $path = $this->constructPath($node);
        $this->groupChildrenDict[$path] = $node;
    }

    /**
     * use child node's identifier for now until better idea shows up
     * @param NodeInterface $node
     * @return string
     */
    public function constructPath(NodeInterface $node):string
    {
        return $this->identifier . DIRECTORY_SEPARATOR . $node->identifier;
    }


}
<?php

namespace  Edu\IU\RSB\StructuredDataNodes;


class GroupNode extends BaseNode{

    public function __construct(string $identifier, BaseNode $node = null)
    {
        parent::__construct('group', $identifier);
        $this->nodeArray['structuredDataNodes'] = ['structuredDataNode' => null];
        if(!is_null($node)){
            $this->addChild($node);
        }

    }

    public function addChild(BaseNode $node):void
    {
        // empty
        if (is_null($this->nodeArray['structuredDataNodes']['structuredDataNode'])){
            $this->nodeArray['structuredDataNodes']['structuredDataNode'] = $node->getNodeArray();
            // only 1
        }elseif(!array_is_list($this->nodeArray['structuredDataNodes']['structuredDataNode'])){
            $theOneChildNode = $this->nodeArray['structuredDataNodes']['structuredDataNode'];
            $this->nodeArray['structuredDataNodes']['structuredDataNode'] = [];
            $this->nodeArray['structuredDataNodes']['structuredDataNode'][] = $theOneChildNode;
            $this->nodeArray['structuredDataNodes']['structuredDataNode'][] = $node->getNodeArray();
            // multiple
        }else{
            $this->nodeArray['structuredDataNodes']['structuredDataNode'][] = $node->getNodeArray();
        }

    }
}
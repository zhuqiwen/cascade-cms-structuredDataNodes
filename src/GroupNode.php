<?php

namespace  Edu\IU\RSB\StructuredDataNodes;


class GroupNode extends BaseNode{

    public function __construct(string $identifier, BaseNode $node = null)
    {
        parent::__construct('group', $identifier);
        $this->setValue('structuredDataNodes', ['structuredDataNode' => null]) ;
        if(!is_null($node)){
            $this->addChild($node);
        }

    }

    public function addChild(BaseNode $node):void
    {
        // empty
        $thisNodeArray = $this->getNodeArray();
        if (is_null($thisNodeArray['structuredDataNodes']['structuredDataNode'])){
            $this->setValue('structuredDataNodes', ['structuredDataNode' => $node->getNodeArray()]);
            // only 1
        }elseif(!array_is_list($thisNodeArray['structuredDataNodes']['structuredDataNode'])){
            $theOneChildNode = $thisNodeArray['structuredDataNodes']['structuredDataNode'];
            $tmpArray = [
                'structuredDataNode' => [
                    $theOneChildNode,
                    $node->getNodeArray()
                ]
            ];
            $this->setValue('structuredDataNodes', $tmpArray);
            // multiple
        }else{
            $tmpArray = $thisNodeArray['structuredDataNodes'];
            $tmpArray['structuredDataNode'][] = $node->getNodeArray();
            $this->setValue('structuredDataNodes', $tmpArray);
        }

    }
}
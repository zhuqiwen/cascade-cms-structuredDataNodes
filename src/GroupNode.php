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
//        $thisNodeArray = $this->getNodeArray();
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
}
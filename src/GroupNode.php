<?php

namespace  Edu\IU\RSB\StructuredDataNodes;


class GroupNode extends BaseNode{

    public function __construct(string $identifier, array $nodeArray)
    {

        if(!key_exists('structuredDataNode', $nodeArray)){
            throw new \RuntimeException('2nd parameter\'s $nodeArray["structuredDataNodes"] should contain key named "structuredDataNode"');
        }

        if(!is_array($nodeArray['structuredDataNode'])){
            throw new \RuntimeException('in 2nd parameter, the value of "structuredDataNode" should be array');
        }
        parent::__construct('group', $identifier);

        $this->nodeArray['structuredDataNodes'] = $nodeArray;
    }
}
<?php

namespace unit;


use Edu\IU\RSB\StructuredDataNodes\BaseNode;


class GroupNodeTest extends BaseTest{
    private function getClassNameWithoutNamespace(BaseNode $node):string
    {
        return basename(str_replace('\\', '/', get_class($node)));

    }

    private function isGroupNode(BaseNode $node):bool
    {
        return $this->getClassNameWithoutNamespace($node) == 'GroupNode';
    }

    public function testGetAllDescendantNodesByPathReturnsEmptyArrayForEmptyPath()
    {
        foreach (self::$mockDataIET as $node) {
            if ($this->isGroupNode($node)){
                $result = $node->getAllDescendantNodesByPath('');
                $this->assertSame([], $result);
            }
        }

        foreach (self::$mockDataIWF as $node) {
            if ($this->isGroupNode($node)){
                $result = $node->getAllDescendantNodesByPath('');
                $this->assertSame([], $result);
            }
        }
    }

    public function testGetAllDescendantNodesByPathReturnsArrayWithExpectedSize()
    {
        //IET framework data
        $sectionGroupNode = self::$mockDataIET[3];
        $chunksArray = $sectionGroupNode->getAllDescendantNodesByPath('column/chunk');
        $this->assertSame(4, sizeof($chunksArray));

        $sectionGroupNode = self::$mockDataIET[4];
        $chunksArray = $sectionGroupNode->getAllDescendantNodesByPath('column/chunk');
        $this->assertSame(1, sizeof($chunksArray));

        //IU web framework data
        $sectionGroupNode = self::$mockDataIWF[3];
        $foldsArray = $sectionGroupNode->getAllDescendantNodesByPath('chunk/details/fold');
        $this->assertSame(3, sizeof($foldsArray));

        $sectionGroupNode = self::$mockDataIWF[4];
        $foldsArray = $sectionGroupNode->getAllDescendantNodesByPath('chunk/details/fold');
        $this->assertSame(7, sizeof($foldsArray));
    }

    public function testGetAllDescendantNodesByPathReturnsChildren()
    {
        $socialMediaGroupNode = self::$mockDataIET[1];
        $useSocialMediaMeta = $socialMediaGroupNode->getAllDescendantNodesByPath('share/use');
        $this->assertSame(1, sizeof($useSocialMediaMeta));
        $useSocialMediaMeta = $useSocialMediaMeta[0];
        $this->assertSame('::CONTENT-XML-CHECKBOX::Yes', $useSocialMediaMeta->text);

        $bannerGroupNode = self::$mockDataIET[2];
        $linkInternalNodesArray = $bannerGroupNode->getAllDescendantNodesByPath('link-internal');
        $this->assertSame(1, sizeof($linkInternalNodesArray));
        $linkInternalNode = $linkInternalNodesArray[0];
        $this->assertSame('page,file,symlink', $linkInternalNode->assetType);
        $this->assertSame('37e6ce4a814f4e103b8fcdaf255ea742', $linkInternalNode->pageId);
        $this->assertSame('index', $linkInternalNode->pagePath);


        $sectionGroupNode = self::$mockDataIET[3];
        $chunkTypeNodesArray = $sectionGroupNode->getAllDescendantNodesByPath('column/chunk/type');
        $this->assertSame(4, sizeof($chunkTypeNodesArray));
        $expectedTypes = ['Accordion', 'Table', 'Text', 'Table'];
        $types = [];
        foreach ($chunkTypeNodesArray as $chunkTypeNode) {
            $types[] = $chunkTypeNode->text;
        }
        $this->assertSame($expectedTypes, $types);

        $backgroundImageNodesArray = $sectionGroupNode->getAllDescendantNodesByPath('background/bg-image');
        $this->assertSame(1, sizeof($backgroundImageNodesArray));
        $backgroundImageNode = $backgroundImageNodesArray[0];
        $this->assertSame('file', $backgroundImageNode->assetType);
        $this->assertSame('e7ca9c39814f4e1076cbe080d017ea68', $backgroundImageNode->fileId);
        $this->assertSame('old-site/images/slider-primary-img-2-1.jpg', $backgroundImageNode->filePath);


        $bannerGroupNode = self::$mockDataIWF[2];
        $bgColorNodesArray = $bannerGroupNode->getAllDescendantNodesByPath('background/bg-color');
        $this->assertSame(1, sizeof($bgColorNodesArray));
        $bgColorNode = $bgColorNodesArray[0];
        $this->assertSame('Light Gray', $bgColorNode->text);

        $sectionGroupNode = self::$mockDataIWF[3];
        $chunkTypeNodesArray = $sectionGroupNode->getAllDescendantNodesByPath('chunk/type');
        $this->assertSame(1, sizeof($chunkTypeNodesArray));
        $chunkTypeNode = $chunkTypeNodesArray[0];
        $this->assertSame('Accordion', $chunkTypeNode->text);
    }

    public function testGetAllFirstSingleDescendantNodesByPath()
    {
        $sectionGroupNode = self::$mockDataIWF[4];
        $chunkTypeNodesArray = $sectionGroupNode->getAllDescendantNodesByPath('chunk');
        $this->assertSame(3, sizeof($chunkTypeNodesArray));
        $firstChunkNode = $sectionGroupNode->getFirstDescendantNodeByPath('chunk');
        $this->assertSame($firstChunkNode, $chunkTypeNodesArray[0]);
        $firstChunkNodeB = $sectionGroupNode->getSingleDescendantNodeByPath('chunk', 0);
        $this->assertSame($firstChunkNode, $firstChunkNodeB);

    }


}

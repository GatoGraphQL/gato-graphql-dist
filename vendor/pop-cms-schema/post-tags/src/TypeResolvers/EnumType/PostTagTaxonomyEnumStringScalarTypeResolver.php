<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostTags\TypeResolvers\EnumType;

use PoPCMSSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoPCMSSchema\Tags\TypeResolvers\EnumType\AbstractTagTaxonomyEnumStringScalarTypeResolver;
/** @internal */
class PostTagTaxonomyEnumStringScalarTypeResolver extends AbstractTagTaxonomyEnumStringScalarTypeResolver
{
    /**
     * @var \PoPCMSSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface|null
     */
    private $postTagTypeAPI;
    public final function setPostTagTypeAPI(PostTagTypeAPIInterface $postTagTypeAPI) : void
    {
        $this->postTagTypeAPI = $postTagTypeAPI;
    }
    protected final function getPostTagTypeAPI() : PostTagTypeAPIInterface
    {
        if ($this->postTagTypeAPI === null) {
            /** @var PostTagTypeAPIInterface */
            $postTagTypeAPI = $this->instanceManager->getInstance(PostTagTypeAPIInterface::class);
            $this->postTagTypeAPI = $postTagTypeAPI;
        }
        return $this->postTagTypeAPI;
    }
    public function getTypeName() : string
    {
        return 'PostTagTaxonomyEnumString';
    }
    public function getTypeDescription() : string
    {
        return \sprintf($this->__('Post tag taxonomies (available for querying via the API), with possible values: `"%s"`.', 'tags'), \implode('"`, `"', $this->getConsolidatedPossibleValues()));
    }
    protected function getRegisteredCustomPostTagTaxonomyNames() : ?array
    {
        return $this->getPostTagTypeAPI()->getRegisteredPostTagTaxonomyNames();
    }
}

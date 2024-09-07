<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostCategoryMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CategoryMutations\TypeResolvers\UnionType\AbstractRootCreateCategoryTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostCategoryMutations\RelationalTypeDataLoaders\UnionType\RootCreatePostCategoryTermMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
/** @internal */
class RootCreatePostCategoryTermMutationErrorPayloadUnionTypeResolver extends AbstractRootCreateCategoryTermMutationErrorPayloadUnionTypeResolver
{
    /**
     * @var \PoPCMSSchema\PostCategoryMutations\RelationalTypeDataLoaders\UnionType\RootCreatePostCategoryTermMutationErrorPayloadUnionTypeDataLoader|null
     */
    private $rootCreatePostCategoryTermMutationErrorPayloadUnionTypeDataLoader;
    public final function setRootCreatePostCategoryTermMutationErrorPayloadUnionTypeDataLoader(RootCreatePostCategoryTermMutationErrorPayloadUnionTypeDataLoader $rootCreatePostCategoryTermMutationErrorPayloadUnionTypeDataLoader) : void
    {
        $this->rootCreatePostCategoryTermMutationErrorPayloadUnionTypeDataLoader = $rootCreatePostCategoryTermMutationErrorPayloadUnionTypeDataLoader;
    }
    protected final function getRootCreatePostCategoryTermMutationErrorPayloadUnionTypeDataLoader() : RootCreatePostCategoryTermMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootCreatePostCategoryTermMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootCreatePostCategoryTermMutationErrorPayloadUnionTypeDataLoader */
            $rootCreatePostCategoryTermMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootCreatePostCategoryTermMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootCreatePostCategoryTermMutationErrorPayloadUnionTypeDataLoader = $rootCreatePostCategoryTermMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootCreatePostCategoryTermMutationErrorPayloadUnionTypeDataLoader;
    }
    public function getTypeName() : string
    {
        return 'RootCreatePostCategoryTermMutationErrorPayloadUnion';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Union of \'Error Payload\' types when creating a post category term', 'post-mutations');
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getRootCreatePostCategoryTermMutationErrorPayloadUnionTypeDataLoader();
    }
}

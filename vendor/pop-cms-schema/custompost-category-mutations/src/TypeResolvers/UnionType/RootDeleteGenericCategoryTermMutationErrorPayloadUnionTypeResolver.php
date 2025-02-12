<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CategoryMutations\TypeResolvers\UnionType\AbstractRootDeleteCategoryTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMutations\RelationalTypeDataLoaders\UnionType\RootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
/** @internal */
class RootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver extends AbstractRootDeleteCategoryTermMutationErrorPayloadUnionTypeResolver
{
    /**
     * @var \PoPCMSSchema\CustomPostCategoryMutations\RelationalTypeDataLoaders\UnionType\RootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader|null
     */
    private $rootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader;
    protected final function getRootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader() : RootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader */
            $rootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader = $rootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader;
    }
    public function getTypeName() : string
    {
        return 'RootDeleteGenericCategoryTermMutationErrorPayloadUnion';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Union of \'Error Payload\' types when deleting a category term', 'post-mutations');
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getRootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader();
    }
}

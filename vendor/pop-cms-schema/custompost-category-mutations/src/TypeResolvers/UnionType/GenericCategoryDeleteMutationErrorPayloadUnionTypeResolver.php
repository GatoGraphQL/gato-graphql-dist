<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CategoryMutations\TypeResolvers\UnionType\AbstractCategoryDeleteMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMutations\RelationalTypeDataLoaders\UnionType\GenericCategoryDeleteMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
/** @internal */
class GenericCategoryDeleteMutationErrorPayloadUnionTypeResolver extends AbstractCategoryDeleteMutationErrorPayloadUnionTypeResolver
{
    /**
     * @var \PoPCMSSchema\CustomPostCategoryMutations\RelationalTypeDataLoaders\UnionType\GenericCategoryDeleteMutationErrorPayloadUnionTypeDataLoader|null
     */
    private $genericCategoryDeleteMutationErrorPayloadUnionTypeDataLoader;
    protected final function getGenericCategoryDeleteMutationErrorPayloadUnionTypeDataLoader() : GenericCategoryDeleteMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->genericCategoryDeleteMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var GenericCategoryDeleteMutationErrorPayloadUnionTypeDataLoader */
            $genericCategoryDeleteMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(GenericCategoryDeleteMutationErrorPayloadUnionTypeDataLoader::class);
            $this->genericCategoryDeleteMutationErrorPayloadUnionTypeDataLoader = $genericCategoryDeleteMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->genericCategoryDeleteMutationErrorPayloadUnionTypeDataLoader;
    }
    public function getTypeName() : string
    {
        return 'GenericCategoryDeleteMutationErrorPayloadUnion';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Union of \'Error Payload\' types when deleting a category term (using nested mutations)', 'post-mutations');
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getGenericCategoryDeleteMutationErrorPayloadUnionTypeDataLoader();
    }
}

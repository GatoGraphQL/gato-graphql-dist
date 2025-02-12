<?php

declare (strict_types=1);
namespace PoPCMSSchema\Media\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPCMSSchema\Media\RelationalTypeDataLoaders\ObjectType\MediaObjectTypeDataLoader;
use PoPCMSSchema\Media\TypeAPIs\MediaTypeAPIInterface;
/** @internal */
class MediaObjectTypeResolver extends AbstractObjectTypeResolver
{
    /**
     * @var \PoPCMSSchema\Media\TypeAPIs\MediaTypeAPIInterface|null
     */
    private $mediaTypeAPI;
    /**
     * @var \PoPCMSSchema\Media\RelationalTypeDataLoaders\ObjectType\MediaObjectTypeDataLoader|null
     */
    private $mediaObjectTypeDataLoader;
    protected final function getMediaTypeAPI() : MediaTypeAPIInterface
    {
        if ($this->mediaTypeAPI === null) {
            /** @var MediaTypeAPIInterface */
            $mediaTypeAPI = $this->instanceManager->getInstance(MediaTypeAPIInterface::class);
            $this->mediaTypeAPI = $mediaTypeAPI;
        }
        return $this->mediaTypeAPI;
    }
    protected final function getMediaObjectTypeDataLoader() : MediaObjectTypeDataLoader
    {
        if ($this->mediaObjectTypeDataLoader === null) {
            /** @var MediaObjectTypeDataLoader */
            $mediaObjectTypeDataLoader = $this->instanceManager->getInstance(MediaObjectTypeDataLoader::class);
            $this->mediaObjectTypeDataLoader = $mediaObjectTypeDataLoader;
        }
        return $this->mediaObjectTypeDataLoader;
    }
    public function getTypeName() : string
    {
        return 'Media';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Media elements (such as images, videos, etc), attached to a post or independent', 'media');
    }
    /**
     * @return string|int|null
     */
    public function getID(object $object)
    {
        $media = $object;
        return $this->getMediaTypeAPI()->getMediaItemID($media);
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getMediaObjectTypeDataLoader();
    }
}

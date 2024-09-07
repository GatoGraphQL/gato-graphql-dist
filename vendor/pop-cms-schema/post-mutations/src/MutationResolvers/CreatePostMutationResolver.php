<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMutations\MutationResolvers\CreateCustomPostMutationResolverTrait;
/** @internal */
class CreatePostMutationResolver extends \PoPCMSSchema\PostMutations\MutationResolvers\AbstractCreateOrUpdatePostMutationResolver
{
    use CreateCustomPostMutationResolverTrait;
}

<?php

declare (strict_types=1);
namespace PoPCMSSchema\PageMutations\MutationResolvers;

/** @internal */
class PayloadableUpdatePageMutationResolver extends \PoPCMSSchema\PageMutations\MutationResolvers\AbstractCreateUpdatePageMutationResolver
{
    use \PoPCMSSchema\PageMutations\MutationResolvers\PayloadablePageMutationResolverTrait;
}

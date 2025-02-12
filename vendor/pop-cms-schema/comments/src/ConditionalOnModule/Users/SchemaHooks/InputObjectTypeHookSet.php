<?php

declare (strict_types=1);
namespace PoPCMSSchema\Comments\ConditionalOnModule\Users\SchemaHooks;

use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\FilterCommentsByCommentAuthorInputObjectTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\FilterCommentsByCustomPostAuthorInputObjectTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\RootCommentsFilterInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
/** @internal */
class InputObjectTypeHookSet extends AbstractHookSet
{
    /**
     * @var \PoPCMSSchema\Comments\TypeResolvers\InputObjectType\FilterCommentsByCommentAuthorInputObjectTypeResolver|null
     */
    private $filterCommentsByCommentAuthorInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Comments\TypeResolvers\InputObjectType\FilterCommentsByCustomPostAuthorInputObjectTypeResolver|null
     */
    private $filterCommentsByCustomPostAuthorInputObjectTypeResolver;
    protected final function getFilterCommentsByCommentAuthorInputObjectTypeResolver() : FilterCommentsByCommentAuthorInputObjectTypeResolver
    {
        if ($this->filterCommentsByCommentAuthorInputObjectTypeResolver === null) {
            /** @var FilterCommentsByCommentAuthorInputObjectTypeResolver */
            $filterCommentsByCommentAuthorInputObjectTypeResolver = $this->instanceManager->getInstance(FilterCommentsByCommentAuthorInputObjectTypeResolver::class);
            $this->filterCommentsByCommentAuthorInputObjectTypeResolver = $filterCommentsByCommentAuthorInputObjectTypeResolver;
        }
        return $this->filterCommentsByCommentAuthorInputObjectTypeResolver;
    }
    protected final function getFilterCommentsByCustomPostAuthorInputObjectTypeResolver() : FilterCommentsByCustomPostAuthorInputObjectTypeResolver
    {
        if ($this->filterCommentsByCustomPostAuthorInputObjectTypeResolver === null) {
            /** @var FilterCommentsByCustomPostAuthorInputObjectTypeResolver */
            $filterCommentsByCustomPostAuthorInputObjectTypeResolver = $this->instanceManager->getInstance(FilterCommentsByCustomPostAuthorInputObjectTypeResolver::class);
            $this->filterCommentsByCustomPostAuthorInputObjectTypeResolver = $filterCommentsByCustomPostAuthorInputObjectTypeResolver;
        }
        return $this->filterCommentsByCustomPostAuthorInputObjectTypeResolver;
    }
    protected function init() : void
    {
        App::addFilter(HookNames::INPUT_FIELD_NAME_TYPE_RESOLVERS, \Closure::fromCallable([$this, 'getInputFieldNameTypeResolvers']), 10, 2);
        App::addFilter(HookNames::INPUT_FIELD_DESCRIPTION, \Closure::fromCallable([$this, 'getInputFieldDescription']), 10, 3);
    }
    /**
     * @param array<string,InputTypeResolverInterface> $inputFieldNameTypeResolvers
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(array $inputFieldNameTypeResolvers, InputObjectTypeResolverInterface $inputObjectTypeResolver) : array
    {
        if (!$inputObjectTypeResolver instanceof RootCommentsFilterInputObjectTypeResolver) {
            return $inputFieldNameTypeResolvers;
        }
        return \array_merge($inputFieldNameTypeResolvers, ['author' => $this->getFilterCommentsByCommentAuthorInputObjectTypeResolver(), 'customPostAuthor' => $this->getFilterCommentsByCustomPostAuthorInputObjectTypeResolver()]);
    }
    public function getInputFieldDescription(?string $inputFieldDescription, InputObjectTypeResolverInterface $inputObjectTypeResolver, string $inputFieldName) : ?string
    {
        if (!$inputObjectTypeResolver instanceof RootCommentsFilterInputObjectTypeResolver) {
            return $inputFieldDescription;
        }
        switch ($inputFieldName) {
            case 'author':
                return $this->__('Filter comments by author', 'comments');
            case 'customPostAuthor':
                return $this->__('Filter comments added to custom posts from the given authors', 'comments');
            default:
                return $inputFieldDescription;
        }
    }
}

<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\BlockCategories;

use GatoGraphQL\GatoGraphQL\Registries\CustomPostTypeRegistryInterface;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\CustomPostTypeInterface;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLEndpointCustomPostTypeInterface;

/**
 * It comprises the endpoint and the persisted query CPTs
 */
class EndpointBlockCategory extends AbstractBlockCategory
{
    public const ENDPOINT_BLOCK_CATEGORY = 'gatographql-query-exec';

    /** @var GraphQLEndpointCustomPostTypeInterface[] */
    protected $graphqlEndpointCustomPostTypeServices;

    /**
     * @var \GatoGraphQL\GatoGraphQL\Registries\CustomPostTypeRegistryInterface|null
     */
    private $customPostTypeRegistry;

    final public function setCustomPostTypeRegistry(CustomPostTypeRegistryInterface $customPostTypeRegistry): void
    {
        $this->customPostTypeRegistry = $customPostTypeRegistry;
    }
    final protected function getCustomPostTypeRegistry(): CustomPostTypeRegistryInterface
    {
        if ($this->customPostTypeRegistry === null) {
            /** @var CustomPostTypeRegistryInterface */
            $customPostTypeRegistry = $this->instanceManager->getInstance(CustomPostTypeRegistryInterface::class);
            $this->customPostTypeRegistry = $customPostTypeRegistry;
        }
        return $this->customPostTypeRegistry;
    }

    public function isServiceEnabled(): bool
    {
        return $this->getGraphQLEndpointCustomPostTypeServices() !== [];
    }

    /**
     * Custom Post Type for which to enable the block category
     *
     * @return string[]
     */
    public function getCustomPostTypes(): array
    {
        return array_map(
            function (CustomPostTypeInterface $customPostTypeService) {
                return $customPostTypeService->getCustomPostType();
            },
            $this->getGraphQLEndpointCustomPostTypeServices()
        );
    }

    /**
     * Block category's slug
     */
    protected function getBlockCategorySlug(): string
    {
        return self::ENDPOINT_BLOCK_CATEGORY;
    }

    /**
     * Block category's title
     */
    protected function getBlockCategoryTitle(): string
    {
        return __('Query execution (endpoint/persisted query)', 'gatographql');
    }

    /**
     * @return GraphQLEndpointCustomPostTypeInterface[]
     */
    protected function getGraphQLEndpointCustomPostTypeServices(): array
    {
        if ($this->graphqlEndpointCustomPostTypeServices === null) {
            $customPostTypeServices = $this->getCustomPostTypeRegistry()->getCustomPostTypes();
            $this->graphqlEndpointCustomPostTypeServices = array_values(array_filter(
                $customPostTypeServices,
                function (CustomPostTypeInterface $customPostTypeService) {
                    return $customPostTypeService instanceof GraphQLEndpointCustomPostTypeInterface;
                }
            ));
        }
        return $this->graphqlEndpointCustomPostTypeServices;
    }
}

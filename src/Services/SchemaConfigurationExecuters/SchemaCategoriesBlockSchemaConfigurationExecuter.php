<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfigurationExecuters;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaTypeModuleResolver;
use GatoGraphQL\GatoGraphQL\Services\Blocks\BlockInterface;
use GatoGraphQL\GatoGraphQL\Services\Blocks\SchemaConfigSchemaCategoriesBlock;
use PoPCMSSchema\Categories\Environment as CategoriesEnvironment;
use PoPCMSSchema\Categories\Module as CategoriesModule;
use PoP\Root\Module\ModuleConfigurationHelpers;

class SchemaCategoriesBlockSchemaConfigurationExecuter extends AbstractCustomizableConfigurationBlockSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    /**
     * @var \GatoGraphQL\GatoGraphQL\Services\Blocks\SchemaConfigSchemaCategoriesBlock|null
     */
    private $schemaConfigCategoriesBlock;

    final protected function getSchemaConfigSchemaCategoriesBlock(): SchemaConfigSchemaCategoriesBlock
    {
        if ($this->schemaConfigCategoriesBlock === null) {
            /** @var SchemaConfigSchemaCategoriesBlock */
            $schemaConfigCategoriesBlock = $this->instanceManager->getInstance(SchemaConfigSchemaCategoriesBlock::class);
            $this->schemaConfigCategoriesBlock = $schemaConfigCategoriesBlock;
        }
        return $this->schemaConfigCategoriesBlock;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaTypeModuleResolver::SCHEMA_CATEGORIES;
    }

    /**
     * @param array<string,mixed> $schemaConfigBlockDataItem
     */
    protected function doExecuteBlockSchemaConfiguration(array $schemaConfigBlockDataItem): void
    {
        $includedCategoryTaxonomies = $schemaConfigBlockDataItem['attrs'][SchemaConfigSchemaCategoriesBlock::ATTRIBUTE_NAME_INCLUDED_CATEGORY_TAXONOMIES] ?? [];
        /**
         * Define the settings value through a hook.
         * Execute last so it overrides the default settings
         */
        $hookName = ModuleConfigurationHelpers::getHookName(
            CategoriesModule::class,
            CategoriesEnvironment::QUERYABLE_CATEGORY_TAXONOMIES
        );
        App::addFilter(
            $hookName,
            function () use ($includedCategoryTaxonomies) {
                return $includedCategoryTaxonomies;
            },
            PHP_INT_MAX
        );
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigSchemaCategoriesBlock();
    }
}

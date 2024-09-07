<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostCategoryMutations;

use PoP\Root\Module\ModuleInterface;
use PoP\Root\Module\AbstractModule;
/** @internal */
class Module extends AbstractModule
{
    protected function requiresSatisfyingModule() : bool
    {
        return \true;
    }
    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedModuleClasses() : array
    {
        return [\PoPCMSSchema\CustomPostMutations\Module::class, \PoPCMSSchema\Categories\Module::class, \PoPCMSSchema\TaxonomyMutations\Module::class];
    }
    /**
     * Initialize services
     *
     * @param array<class-string<ModuleInterface>> $skipSchemaModuleClasses
     */
    protected function initializeContainerServices(bool $skipSchema, array $skipSchemaModuleClasses) : void
    {
        $this->initServices(\dirname(__DIR__));
        $this->initSchemaServices(\dirname(__DIR__), $skipSchema);
    }
}

<?php

declare (strict_types=1);
namespace PoPCMSSchema\SchemaCommons;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;
/** @internal */
class ModuleConfiguration extends AbstractModuleConfiguration
{
    /**
     * Remove unwanted data added to the REQUEST_URI, replacing
     * it with the website home URL.
     *
     * Eg: the language information from a Multisite network
     * based on subfolders (https://domain.com/en/...)
     */
    public function overrideRequestURI() : bool
    {
        $envVariable = \PoPCMSSchema\SchemaCommons\Environment::OVERRIDE_REQUEST_URI;
        $defaultValue = \false;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
}

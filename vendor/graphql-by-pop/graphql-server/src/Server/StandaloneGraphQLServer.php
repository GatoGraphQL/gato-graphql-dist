<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\Server;

use GraphQLByPoP\GraphQLServer\AppStateProviderServices\GraphQLServerAppStateProviderServiceInterface;
use GraphQLByPoP\GraphQLServer\Module;
use PoPAPI\API\QueryParsing\GraphQLParserHelperServiceInterface;
use PoP\ComponentModel\App;
use PoP\ComponentModel\AppThread;
use PoP\Root\AppLoader;
use PoP\Root\AppLoaderInterface;
use PoP\Root\Container\ContainerCacheConfiguration;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\Module\ModuleInterface;
use PoP\Root\StateManagers\HookManager;
use PoP\Root\StateManagers\HookManagerInterface;
use GatoExternalPrefixByGatoGraphQL\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
/**
 * This class must be used when there is no underlying
 * PoP architecture that renders the response, hence the
 * constructor must emulate the initialization of the
 * whole application.
 * @internal
 */
class StandaloneGraphQLServer extends \GraphQLByPoP\GraphQLServer\Server\AbstractGraphQLServer
{
    /**
     * @var array<class-string<ModuleInterface>, array<string, mixed>>
     * @readonly
     */
    private $moduleClassConfiguration = [];
    /**
     * @var array<class-string<CompilerPassInterface>>
     * @readonly
     */
    private $systemContainerCompilerPassClasses = [];
    /**
     * @var array<class-string<CompilerPassInterface>>
     * @readonly
     */
    private $applicationContainerCompilerPassClasses = [];
    /**
     * @readonly
     * @var \PoP\Root\Container\ContainerCacheConfiguration|null
     */
    private $containerCacheConfiguration;
    /**
     * @var array<class-string<ModuleInterface>>
     * @readonly
     */
    private $moduleClasses;
    /**
     * @var \PoPAPI\API\QueryParsing\GraphQLParserHelperServiceInterface|null
     */
    private $graphQLParserHelperService;
    /**
     * @var \GraphQLByPoP\GraphQLServer\AppStateProviderServices\GraphQLServerAppStateProviderServiceInterface|null
     */
    private $graphQLServerAppStateProviderService;
    protected final function getGraphQLParserHelperService() : GraphQLParserHelperServiceInterface
    {
        if ($this->graphQLParserHelperService === null) {
            /** @var GraphQLParserHelperServiceInterface */
            $graphQLParserHelperService = InstanceManagerFacade::getInstance()->getInstance(GraphQLParserHelperServiceInterface::class);
            $this->graphQLParserHelperService = $graphQLParserHelperService;
        }
        return $this->graphQLParserHelperService;
    }
    protected final function getGraphQLServerAppStateProviderService() : GraphQLServerAppStateProviderServiceInterface
    {
        if ($this->graphQLServerAppStateProviderService === null) {
            /** @var GraphQLServerAppStateProviderServiceInterface */
            $graphQLServerAppStateProviderService = InstanceManagerFacade::getInstance()->getInstance(GraphQLServerAppStateProviderServiceInterface::class);
            $this->graphQLServerAppStateProviderService = $graphQLServerAppStateProviderService;
        }
        return $this->graphQLServerAppStateProviderService;
    }
    /**
     * @param array<class-string<ModuleInterface>> $moduleClasses The component classes to initialize, including those dealing with the schema elements (posts, users, comments, etc)
     * @param array<class-string<ModuleInterface>,array<string,mixed>> $moduleClassConfiguration Predefined configuration for the components
     * @param array<class-string<CompilerPassInterface>> $systemContainerCompilerPassClasses
     * @param array<class-string<CompilerPassInterface>> $applicationContainerCompilerPassClasses
     */
    public function __construct(array $moduleClasses, array $moduleClassConfiguration = [], array $systemContainerCompilerPassClasses = [], array $applicationContainerCompilerPassClasses = [], ?ContainerCacheConfiguration $containerCacheConfiguration = null)
    {
        $this->moduleClassConfiguration = $moduleClassConfiguration;
        $this->systemContainerCompilerPassClasses = $systemContainerCompilerPassClasses;
        $this->applicationContainerCompilerPassClasses = $applicationContainerCompilerPassClasses;
        $this->containerCacheConfiguration = $containerCacheConfiguration;
        $this->moduleClasses = \array_merge($moduleClasses, [
            /**
             * This is the one Module that is required to produce the GraphQL server.
             * The other classes provide the schema and extra functionality.
             */
            Module::class,
        ]);
        App::setAppThread(new AppThread());
        App::initialize($this->getAppLoader(), $this->getHookManager());
        $appLoader = App::getAppLoader();
        $appLoader->addModuleClassesToInitialize($this->moduleClasses);
        $appLoader->initializeModules();
        // Inject the Compiler Passes
        $appLoader->addSystemContainerCompilerPassClasses($this->systemContainerCompilerPassClasses);
        $appLoader->setContainerCacheConfiguration($this->containerCacheConfiguration);
        $appLoader->bootSystem();
        /**
         * Only after initializing the System Container,
         * we can obtain the configuration (which may depend on hooks).
         */
        $appLoader->addModuleClassConfiguration($this->moduleClassConfiguration);
        // Inject the Compiler Passes
        $appLoader->addApplicationContainerCompilerPassClasses($this->applicationContainerCompilerPassClasses);
        // Boot the application
        $appLoader->bootApplication();
        /**
         * After booting the application, we can access the Application Container services.
         * Explicitly set the required state to execute GraphQL queries.
         */
        $graphQLRequestAppState = \array_merge($this->getGraphQLServerAppStateProviderService()->getGraphQLRequestAppState(), ['query' => null]);
        $appLoader->setInitialAppState($graphQLRequestAppState);
        // Finally trigger booting the components
        $appLoader->bootApplicationModules();
    }
    protected function getAppLoader() : AppLoaderInterface
    {
        return new AppLoader();
    }
    protected function getHookManager() : HookManagerInterface
    {
        return new HookManager();
    }
}

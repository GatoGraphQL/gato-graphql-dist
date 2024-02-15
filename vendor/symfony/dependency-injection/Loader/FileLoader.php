<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\DependencyInjection\Loader;

use PrefixedByPoP\Symfony\Component\Config\Exception\FileLocatorFileNotFoundException;
use PrefixedByPoP\Symfony\Component\Config\Exception\LoaderLoadException;
use PrefixedByPoP\Symfony\Component\Config\FileLocatorInterface;
use PrefixedByPoP\Symfony\Component\Config\Loader\FileLoader as BaseFileLoader;
use PrefixedByPoP\Symfony\Component\Config\Loader\Loader;
use PrefixedByPoP\Symfony\Component\Config\Resource\GlobResource;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Alias;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Attribute\AsAlias;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Attribute\Exclude;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Attribute\When;
use PrefixedByPoP\Symfony\Component\DependencyInjection\ChildDefinition;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\RegisterAutoconfigureAttributesPass;
use PrefixedByPoP\Symfony\Component\DependencyInjection\ContainerBuilder;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Definition;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Exception\LogicException;
/**
 * FileLoader is the abstract class used by all built-in loaders that are file based.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @internal
 */
abstract class FileLoader extends BaseFileLoader
{
    public const ANONYMOUS_ID_REGEXP = '/^\\.\\d+_[^~]*+~[._a-zA-Z\\d]{7}$/';
    protected $container;
    protected $isLoadingInstanceof = \false;
    protected $instanceof = [];
    protected $interfaces = [];
    protected $singlyImplemented = [];
    /** @var array<string, Alias> */
    protected $aliases = [];
    protected $autoRegisterAliasesForSinglyImplementedInterfaces = \true;
    public function __construct(ContainerBuilder $container, FileLocatorInterface $locator, ?string $env = null)
    {
        $this->container = $container;
        parent::__construct($locator, $env);
    }
    /**
     * @param bool|string $ignoreErrors Whether errors should be ignored; pass "not_found" to ignore only when the loaded resource is not found
     * @param mixed $resource
     * @return mixed
     */
    public function import($resource, ?string $type = null, $ignoreErrors = \false, ?string $sourceResource = null, $exclude = null)
    {
        $args = \func_get_args();
        if ($ignoreNotFound = 'not_found' === $ignoreErrors) {
            $args[2] = \false;
        } elseif (!\is_bool($ignoreErrors)) {
            throw new \TypeError(\sprintf('Invalid argument $ignoreErrors provided to "%s::import()": boolean or "not_found" expected, "%s" given.', static::class, \get_debug_type($ignoreErrors)));
        }
        try {
            return parent::import(...$args);
        } catch (LoaderLoadException $e) {
            if (!$ignoreNotFound || !($prev = $e->getPrevious()) instanceof FileLocatorFileNotFoundException) {
                throw $e;
            }
            foreach ($prev->getTrace() as $frame) {
                if ('import' === ($frame['function'] ?? null) && \is_a($frame['class'] ?? '', Loader::class, \true)) {
                    break;
                }
            }
            if (__FILE__ !== $frame['file']) {
                throw $e;
            }
        }
        return null;
    }
    /**
     * Registers a set of classes as services using PSR-4 for discovery.
     *
     * @param Definition           $prototype A definition to use as template
     * @param string               $namespace The namespace prefix of classes in the scanned directory
     * @param string               $resource  The directory to look for classes, glob-patterns allowed
     * @param string|string[]|null $exclude   A globbed path of files to exclude or an array of globbed paths of files to exclude
     * @param string|null          $source    The path to the file that defines the auto-discovery rule
     *
     * @return void
     */
    public function registerClasses(Definition $prototype, string $namespace, string $resource, $exclude = null)
    {
        if (\substr_compare($namespace, '\\', -\strlen('\\')) !== 0) {
            throw new InvalidArgumentException(\sprintf('Namespace prefix must end with a "\\": "%s".', $namespace));
        }
        if (!\preg_match('/^(?:[a-zA-Z_\\x7f-\\xff][a-zA-Z0-9_\\x7f-\\xff]*+\\\\)++$/', $namespace)) {
            throw new InvalidArgumentException(\sprintf('Namespace is not a valid PSR-4 prefix: "%s".', $namespace));
        }
        // This can happen with YAML files
        if (\is_array($exclude) && \in_array(null, $exclude, \true)) {
            throw new InvalidArgumentException('The exclude list must not contain a "null" value.');
        }
        // This can happen with XML files
        if (\is_array($exclude) && \in_array('', $exclude, \true)) {
            throw new InvalidArgumentException('The exclude list must not contain an empty value.');
        }
        $source = \func_num_args() > 4 ? \func_get_arg(4) : null;
        $autoconfigureAttributes = new RegisterAutoconfigureAttributesPass();
        $autoconfigureAttributes = $autoconfigureAttributes->accept($prototype) ? $autoconfigureAttributes : null;
        $classes = $this->findClasses($namespace, $resource, (array) $exclude, $autoconfigureAttributes, $source);
        $getPrototype = static function () use($prototype) {
            return clone $prototype;
        };
        $serialized = \serialize($prototype);
        // avoid deep cloning if no definitions are nested
        if (\strpos($serialized, 'O:48:"Symfony\\Component\\DependencyInjection\\Definition"', 55) || \strpos($serialized, 'O:53:"Symfony\\Component\\DependencyInjection\\ChildDefinition"', 55)) {
            // prepare for deep cloning
            foreach (['Arguments', 'Properties', 'MethodCalls', 'Configurator', 'Factory', 'Bindings'] as $key) {
                $serialized = \serialize($prototype->{'get' . $key}());
                if (\strpos($serialized, 'O:48:"Symfony\\Component\\DependencyInjection\\Definition"') || \strpos($serialized, 'O:53:"Symfony\\Component\\DependencyInjection\\ChildDefinition"')) {
                    $getPrototype = static function () use($getPrototype, $key, $serialized) {
                        return $getPrototype()->{'set' . $key}(\unserialize($serialized));
                    };
                }
            }
        }
        unset($serialized);
        foreach ($classes as $class => $errorMessage) {
            if (null === $errorMessage && $autoconfigureAttributes) {
                $r = $this->container->getReflectionClass($class);
                if ($r->getAttributes(Exclude::class)[0] ?? null) {
                    $this->addContainerExcludedTag($class, $source);
                    continue;
                }
                if ($this->env) {
                    $attribute = null;
                    foreach ($r->getAttributes(When::class, \ReflectionAttribute::IS_INSTANCEOF) as $attribute) {
                        if ($this->env === $attribute->newInstance()->env) {
                            $attribute = null;
                            break;
                        }
                    }
                    if (null !== $attribute) {
                        $this->addContainerExcludedTag($class, $source);
                        continue;
                    }
                }
            }
            if (\interface_exists($class, \false)) {
                $this->interfaces[] = $class;
            } else {
                $this->setDefinition($class, $definition = $getPrototype());
                if (null !== $errorMessage) {
                    $definition->addError($errorMessage);
                    continue;
                }
                $definition->setClass($class);
                $interfaces = [];
                foreach (\class_implements($class, \false) as $interface) {
                    $this->singlyImplemented[$interface] = ($this->singlyImplemented[$interface] ?? $class) !== $class ? \false : $class;
                    $interfaces[] = $interface;
                }
                if (!$autoconfigureAttributes) {
                    continue;
                }
                $r = $this->container->getReflectionClass($class);
                $defaultAlias = 1 === \count($interfaces) ? $interfaces[0] : null;
                foreach ($r->getAttributes(AsAlias::class) as $attr) {
                    /** @var AsAlias $attribute */
                    $attribute = $attr->newInstance();
                    $alias = $attribute->id ?? $defaultAlias;
                    $public = $attribute->public;
                    if (null === $alias) {
                        throw new LogicException(\sprintf('Alias cannot be automatically determined for class "%s". If you have used the #[AsAlias] attribute with a class implementing multiple interfaces, add the interface you want to alias to the first parameter of #[AsAlias].', $class));
                    }
                    if (isset($this->aliases[$alias])) {
                        throw new LogicException(\sprintf('The "%s" alias has already been defined with the #[AsAlias] attribute in "%s".', $alias, $this->aliases[$alias]));
                    }
                    $this->aliases[$alias] = new Alias($class, $public);
                }
            }
        }
        foreach ($this->aliases as $alias => $aliasDefinition) {
            $this->container->setAlias($alias, $aliasDefinition);
        }
        if ($this->autoRegisterAliasesForSinglyImplementedInterfaces) {
            $this->registerAliasesForSinglyImplementedInterfaces();
        }
    }
    /**
     * @return void
     */
    public function registerAliasesForSinglyImplementedInterfaces()
    {
        foreach ($this->interfaces as $interface) {
            if (!empty($this->singlyImplemented[$interface]) && !isset($this->aliases[$interface]) && !$this->container->has($interface)) {
                $this->container->setAlias($interface, $this->singlyImplemented[$interface]);
            }
        }
        $this->interfaces = $this->singlyImplemented = $this->aliases = [];
    }
    /**
     * Registers a definition in the container with its instanceof-conditionals.
     *
     * @return void
     */
    protected function setDefinition(string $id, Definition $definition)
    {
        $this->container->removeBindings($id);
        foreach ($definition->getTag('container.error') as $error) {
            if (isset($error['message'])) {
                $definition->addError($error['message']);
            }
        }
        if ($this->isLoadingInstanceof) {
            if (!$definition instanceof ChildDefinition) {
                throw new InvalidArgumentException(\sprintf('Invalid type definition "%s": ChildDefinition expected, "%s" given.', $id, \get_debug_type($definition)));
            }
            $this->instanceof[$id] = $definition;
        } else {
            $this->container->setDefinition($id, $definition->setInstanceofConditionals($this->instanceof));
        }
    }
    private function findClasses(string $namespace, string $pattern, array $excludePatterns, ?RegisterAutoconfigureAttributesPass $autoconfigureAttributes, ?string $source) : array
    {
        $parameterBag = $this->container->getParameterBag();
        $excludePaths = [];
        $excludePrefix = null;
        $excludePatterns = $parameterBag->unescapeValue($parameterBag->resolveValue($excludePatterns));
        foreach ($excludePatterns as $excludePattern) {
            foreach ($this->glob($excludePattern, \true, $resource, \true, \true) as $path => $info) {
                $excludePrefix = $excludePrefix ?? $resource->getPrefix();
                // normalize Windows slashes and remove trailing slashes
                $excludePaths[\rtrim(\str_replace('\\', '/', $path), '/')] = \true;
            }
        }
        $pattern = $parameterBag->unescapeValue($parameterBag->resolveValue($pattern));
        $classes = [];
        $prefixLen = null;
        foreach ($this->glob($pattern, \true, $resource, \false, \false, $excludePaths) as $path => $info) {
            if (null === $prefixLen) {
                $prefixLen = \strlen($resource->getPrefix());
                if ($excludePrefix && \strncmp($excludePrefix, $resource->getPrefix(), \strlen($resource->getPrefix())) !== 0) {
                    throw new InvalidArgumentException(\sprintf('Invalid "exclude" pattern when importing classes for "%s": make sure your "exclude" pattern (%s) is a subset of the "resource" pattern (%s).', $namespace, $excludePattern, $pattern));
                }
            }
            if (isset($excludePaths[\str_replace('\\', '/', $path)])) {
                continue;
            }
            if (\substr_compare($path, '.php', -\strlen('.php')) !== 0) {
                continue;
            }
            $class = $namespace . \ltrim(\str_replace('/', '\\', \substr($path, $prefixLen, -4)), '\\');
            if (!\preg_match('/^[a-zA-Z_\\x7f-\\xff][a-zA-Z0-9_\\x7f-\\xff]*+(?:\\\\[a-zA-Z_\\x7f-\\xff][a-zA-Z0-9_\\x7f-\\xff]*+)*+$/', $class)) {
                continue;
            }
            try {
                $r = $this->container->getReflectionClass($class);
            } catch (\ReflectionException $e) {
                $classes[$class] = $e->getMessage();
                continue;
            }
            // check to make sure the expected class exists
            if (!$r) {
                throw new InvalidArgumentException(\sprintf('Expected to find class "%s" in file "%s" while importing services from resource "%s", but it was not found! Check the namespace prefix used with the resource.', $class, $path, $pattern));
            }
            if ($r->isInstantiable() || $r->isInterface()) {
                $classes[$class] = null;
            }
            if ($autoconfigureAttributes && !$r->isInstantiable()) {
                $autoconfigureAttributes->processClass($this->container, $r);
            }
        }
        // track only for new & removed files
        if ($resource instanceof GlobResource) {
            $this->container->addResource($resource);
        } else {
            foreach ($resource as $path) {
                $this->container->fileExists($path, \false);
            }
        }
        if (null !== $prefixLen) {
            foreach ($excludePaths as $path => $_) {
                $class = $namespace . \ltrim(\str_replace('/', '\\', \substr($path, $prefixLen, \substr_compare($path, '.php', -\strlen('.php')) === 0 ? -4 : null)), '\\');
                $this->addContainerExcludedTag($class, $source);
            }
        }
        return $classes;
    }
    private function addContainerExcludedTag(string $class, ?string $source) : void
    {
        if ($this->container->has($class)) {
            return;
        }
        static $attributes = [];
        if (null !== $source && !isset($attributes[$source])) {
            $attributes[$source] = ['source' => \sprintf('in "%s/%s"', \basename(\dirname($source)), \basename($source))];
        }
        $this->container->register($class, $class)->setAbstract(\true)->addTag('container.excluded', null !== $source ? $attributes[$source] : []);
    }
}

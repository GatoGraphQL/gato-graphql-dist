<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace GatoExternalPrefixByGatoGraphQL\Symfony\Component\DependencyInjection\Compiler;

use GatoExternalPrefixByGatoGraphQL\Symfony\Component\DependencyInjection\ContainerBuilder;
use GatoExternalPrefixByGatoGraphQL\Symfony\Component\DependencyInjection\ContainerInterface;
use GatoExternalPrefixByGatoGraphQL\Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use GatoExternalPrefixByGatoGraphQL\Symfony\Component\DependencyInjection\Reference;
/**
 * Checks that all references are pointing to a valid service.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 * @internal
 */
class CheckExceptionOnInvalidReferenceBehaviorPass extends AbstractRecursivePass
{
    /**
     * @var bool
     */
    protected $skipScalars = \true;
    /**
     * @var mixed[]
     */
    private $serviceLocatorContextIds = [];
    /**
     * @return void
     */
    public function process(ContainerBuilder $container)
    {
        $this->serviceLocatorContextIds = [];
        foreach ($container->findTaggedServiceIds('container.service_locator_context') as $id => $tags) {
            $this->serviceLocatorContextIds[$id] = $tags[0]['id'];
            $container->getDefinition($id)->clearTag('container.service_locator_context');
        }
        try {
            parent::process($container);
        } finally {
            $this->serviceLocatorContextIds = [];
        }
    }
    /**
     * @param mixed $value
     * @return mixed
     */
    protected function processValue($value, bool $isRoot = \false)
    {
        if (!$value instanceof Reference) {
            return parent::processValue($value, $isRoot);
        }
        if (ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE < $value->getInvalidBehavior() || $this->container->has($id = (string) $value)) {
            return $value;
        }
        $currentId = $this->currentId;
        $graph = $this->container->getCompiler()->getServiceReferenceGraph();
        if (isset($this->serviceLocatorContextIds[$currentId])) {
            $currentId = $this->serviceLocatorContextIds[$currentId];
            $locator = $this->container->getDefinition($this->currentId)->getFactory()[0];
            $this->throwServiceNotFoundException($value, $currentId, $locator->getArgument(0));
        }
        if ('.' === $currentId[0] && $graph->hasNode($currentId)) {
            foreach ($graph->getNode($currentId)->getInEdges() as $edge) {
                if (!$edge->getValue() instanceof Reference || ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE < $edge->getValue()->getInvalidBehavior()) {
                    continue;
                }
                $sourceId = $edge->getSourceNode()->getId();
                if ('.' !== $sourceId[0]) {
                    $currentId = $sourceId;
                    break;
                }
                if (isset($this->serviceLocatorContextIds[$sourceId])) {
                    $currentId = $this->serviceLocatorContextIds[$sourceId];
                    $locator = $this->container->getDefinition($this->currentId);
                    $this->throwServiceNotFoundException($value, $currentId, $locator->getArgument(0));
                }
            }
        }
        $this->throwServiceNotFoundException($value, $currentId, $value);
    }
    private function throwServiceNotFoundException(Reference $ref, string $sourceId, $value) : void
    {
        $id = (string) $ref;
        $alternatives = [];
        foreach ($this->container->getServiceIds() as $knownId) {
            if ('' === $knownId || '.' === $knownId[0] || $knownId === $this->currentId) {
                continue;
            }
            $lev = \levenshtein($id, $knownId);
            if ($lev <= \strlen($id) / 3 || \strpos($knownId, $id) !== \false) {
                $alternatives[] = $knownId;
            }
        }
        $pass = new class extends AbstractRecursivePass
        {
            /**
             * @var \Symfony\Component\DependencyInjection\Reference
             */
            public $ref;
            /**
             * @var string
             */
            public $sourceId;
            /**
             * @var mixed[]
             */
            public $alternatives;
            /**
             * @param mixed $value
             * @return mixed
             */
            public function processValue($value, bool $isRoot = \false)
            {
                if ($this->ref !== $value) {
                    return parent::processValue($value, $isRoot);
                }
                $sourceId = $this->sourceId;
                if (null !== $this->currentId && $this->currentId !== (string) $value) {
                    $sourceId = $this->currentId . '" in the container provided to "' . $sourceId;
                }
                throw new ServiceNotFoundException((string) $value, $sourceId, null, $this->alternatives);
            }
        };
        $pass->ref = $ref;
        $pass->sourceId = $sourceId;
        $pass->alternatives = $alternatives;
        $pass->processValue($value, \true);
    }
}

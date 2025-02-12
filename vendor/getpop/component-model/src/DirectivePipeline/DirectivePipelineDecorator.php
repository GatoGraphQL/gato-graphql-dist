<?php

declare (strict_types=1);
namespace PoP\ComponentModel\DirectivePipeline;

use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use GatoExternalPrefixByGatoGraphQL\League\Pipeline\PipelineInterface;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessProviderInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;
/** @internal */
class DirectivePipelineDecorator
{
    /**
     * @readonly
     * @var \League\Pipeline\PipelineInterface
     */
    private $pipeline;
    public function __construct(PipelineInterface $pipeline)
    {
        $this->pipeline = $pipeline;
    }
    /**
     * @param array<array<string|int,EngineIterationFieldSet>> $pipelineIDFieldSet
     * @param array<FieldDataAccessProviderInterface> $pipelineFieldDataAccessProviders
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> $previouslyResolvedIDFieldValues
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     * @param array<FieldDirectiveResolverInterface> $pipelineFieldDirectiveResolvers
     * @param array<string|int,object> $idObjects
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,array<string|int>>>> $unionTypeOutputKeyIDs
     * @param array<string,mixed> $messages
     */
    public function resolveDirectivePipeline(RelationalTypeResolverInterface $relationalTypeResolver, array $pipelineIDFieldSet, array $pipelineFieldDataAccessProviders, array $pipelineFieldDirectiveResolvers, array $idObjects, array $unionTypeOutputKeyIDs, array $previouslyResolvedIDFieldValues, array &$resolvedIDFieldValues, array &$messages, EngineIterationFeedbackStore $engineIterationFeedbackStore) : void
    {
        $payload = $this->pipeline->__invoke(\PoP\ComponentModel\DirectivePipeline\DirectivePipelineUtils::convertArgumentsToPayload($relationalTypeResolver, $pipelineFieldDirectiveResolvers, $idObjects, $unionTypeOutputKeyIDs, $previouslyResolvedIDFieldValues, $pipelineIDFieldSet, $pipelineFieldDataAccessProviders, $resolvedIDFieldValues, $messages, $engineIterationFeedbackStore));
        list($relationalTypeResolver, $pipelineFieldDirectiveResolvers, $idObjects, $unionTypeOutputKeyIDs, $previouslyResolvedIDFieldValues, $pipelineIDFieldSet, $pipelineFieldDataAccessProviders, $resolvedIDFieldValues, $messages, $engineIterationFeedbackStore, ) = \PoP\ComponentModel\DirectivePipeline\DirectivePipelineUtils::extractArgumentsFromPayload($payload);
    }
}

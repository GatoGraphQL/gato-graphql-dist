<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\ComponentProcessors;

use GraphQLByPoP\GraphQLServer\QueryResolution\GraphQLQueryASTTransformationServiceInterface;
use PoPAPI\API\ComponentProcessors\AbstractRelationalFieldQueryDataComponentProcessor;
use PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use SplObjectStorage;
/** @internal */
abstract class AbstractGraphQLRelationalFieldQueryDataComponentProcessor extends AbstractRelationalFieldQueryDataComponentProcessor
{
    /**
     * @var \GraphQLByPoP\GraphQLServer\QueryResolution\GraphQLQueryASTTransformationServiceInterface|null
     */
    private $graphQLQueryASTTransformationService;
    protected final function getGraphQLQueryASTTransformationService() : GraphQLQueryASTTransformationServiceInterface
    {
        if ($this->graphQLQueryASTTransformationService === null) {
            /** @var GraphQLQueryASTTransformationServiceInterface */
            $graphQLQueryASTTransformationService = $this->instanceManager->getInstance(GraphQLQueryASTTransformationServiceInterface::class);
            $this->graphQLQueryASTTransformationService = $graphQLQueryASTTransformationService;
        }
        return $this->graphQLQueryASTTransformationService;
    }
    /**
     * Convert the operations to include the SuperRoot Fields
     *
     * @return SplObjectStorage<OperationInterface,array<FieldInterface|FragmentBondInterface>>
     */
    protected function getOperationFieldOrFragmentBonds(ExecutableDocument $executableDocument) : SplObjectStorage
    {
        $document = $executableDocument->getDocument();
        /** @var OperationInterface[] */
        $operations = $executableDocument->getMultipleOperationsToExecute();
        return $this->getGraphQLQueryASTTransformationService()->prepareOperationFieldAndFragmentBondsForExecution($document, $operations, $document->getFragments());
    }
}

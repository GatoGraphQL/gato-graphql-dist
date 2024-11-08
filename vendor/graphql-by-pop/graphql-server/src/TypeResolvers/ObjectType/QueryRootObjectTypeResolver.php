<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\QueryRoot;
use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\QueryRootObjectTypeDataLoader;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\CanonicalTypeNameTypeResolverTrait;
/** @internal */
class QueryRootObjectTypeResolver extends \GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\AbstractUseRootAsSourceForSchemaObjectTypeResolver
{
    use CanonicalTypeNameTypeResolverTrait;
    /**
     * @var \GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\QueryRootObjectTypeDataLoader|null
     */
    private $queryRootObjectTypeDataLoader;
    protected final function getQueryRootObjectTypeDataLoader() : QueryRootObjectTypeDataLoader
    {
        if ($this->queryRootObjectTypeDataLoader === null) {
            /** @var QueryRootObjectTypeDataLoader */
            $queryRootObjectTypeDataLoader = $this->instanceManager->getInstance(QueryRootObjectTypeDataLoader::class);
            $this->queryRootObjectTypeDataLoader = $queryRootObjectTypeDataLoader;
        }
        return $this->queryRootObjectTypeDataLoader;
    }
    public function getTypeName() : string
    {
        return 'QueryRoot';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Query type, starting from which the query is executed', 'graphql-server');
    }
    /**
     * @return string|int|null
     */
    public function getID(object $object)
    {
        /** @var QueryRoot */
        $queryRoot = $object;
        return $queryRoot->getID();
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getQueryRootObjectTypeDataLoader();
    }
    public function isFieldNameConditionSatisfiedForSchema(ObjectTypeFieldResolverInterface $objectTypeFieldResolver, string $fieldName) : bool
    {
        return !\in_array($fieldName, ['queryRoot', 'mutationRoot']) && $objectTypeFieldResolver->getFieldMutationResolver($this, $fieldName) === null;
    }
}

<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\Schema;
use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\SchemaObjectTypeDataLoader;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\SchemaObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\TypeObjectTypeResolver;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\Root\App;
class RootObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    /**
     * @var \GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\SchemaObjectTypeResolver|null
     */
    private $schemaObjectTypeResolver;
    /**
     * @var \GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\TypeObjectTypeResolver|null
     */
    private $typeObjectTypeResolver;
    /**
     * @var \GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\SchemaObjectTypeDataLoader|null
     */
    private $schemaObjectTypeDataLoader;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    public final function setSchemaObjectTypeResolver(SchemaObjectTypeResolver $schemaObjectTypeResolver) : void
    {
        $this->schemaObjectTypeResolver = $schemaObjectTypeResolver;
    }
    protected final function getSchemaObjectTypeResolver() : SchemaObjectTypeResolver
    {
        if ($this->schemaObjectTypeResolver === null) {
            /** @var SchemaObjectTypeResolver */
            $schemaObjectTypeResolver = $this->instanceManager->getInstance(SchemaObjectTypeResolver::class);
            $this->schemaObjectTypeResolver = $schemaObjectTypeResolver;
        }
        return $this->schemaObjectTypeResolver;
    }
    public final function setTypeObjectTypeResolver(TypeObjectTypeResolver $typeObjectTypeResolver) : void
    {
        $this->typeObjectTypeResolver = $typeObjectTypeResolver;
    }
    protected final function getTypeObjectTypeResolver() : TypeObjectTypeResolver
    {
        if ($this->typeObjectTypeResolver === null) {
            /** @var TypeObjectTypeResolver */
            $typeObjectTypeResolver = $this->instanceManager->getInstance(TypeObjectTypeResolver::class);
            $this->typeObjectTypeResolver = $typeObjectTypeResolver;
        }
        return $this->typeObjectTypeResolver;
    }
    public final function setSchemaObjectTypeDataLoader(SchemaObjectTypeDataLoader $schemaObjectTypeDataLoader) : void
    {
        $this->schemaObjectTypeDataLoader = $schemaObjectTypeDataLoader;
    }
    protected final function getSchemaObjectTypeDataLoader() : SchemaObjectTypeDataLoader
    {
        if ($this->schemaObjectTypeDataLoader === null) {
            /** @var SchemaObjectTypeDataLoader */
            $schemaObjectTypeDataLoader = $this->instanceManager->getInstance(SchemaObjectTypeDataLoader::class);
            $this->schemaObjectTypeDataLoader = $schemaObjectTypeDataLoader;
        }
        return $this->schemaObjectTypeDataLoader;
    }
    public final function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver) : void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    protected final function getStringScalarTypeResolver() : StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [RootObjectTypeResolver::class];
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        if (!App::getState('graphql-introspection-enabled')) {
            return [];
        }
        return ['__schema', '__type'];
    }
    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName) : int
    {
        switch ($fieldName) {
            case '__schema':
            case '__type':
                return SchemaTypeModifiers::NON_NULLABLE;
            default:
                return parent::getFieldTypeModifiers($objectTypeResolver, $fieldName);
        }
    }
    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName) : ?string
    {
        switch ($fieldName) {
            case '__schema':
                return $this->__('The GraphQL schema, exposing what fields can be queried', 'graphql-server');
            case '__type':
                return $this->__('Obtain a specific type from the schema', 'graphql-server');
            default:
                return parent::getFieldDescription($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName) : array
    {
        switch ($fieldName) {
            case '__type':
                return ['name' => $this->getStringScalarTypeResolver()];
            default:
                return parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        }
    }
    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName) : ?string
    {
        switch ([$fieldName => $fieldArgName]) {
            case ['__type' => 'name']:
                return $this->__('The name of the type', 'graphql-server');
            default:
                return parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName);
        }
    }
    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName) : int
    {
        switch ([$fieldName => $fieldArgName]) {
            case ['__type' => 'name']:
                return SchemaTypeModifiers::MANDATORY;
            default:
                return parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }
    }
    /**
     * @return mixed
     */
    public function resolveValue(ObjectTypeResolverInterface $objectTypeResolver, object $object, FieldDataAccessorInterface $fieldDataAccessor, ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore)
    {
        $root = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case '__schema':
                return Schema::ID;
            case '__type':
                // Get an instance of the schema and then execute function `getType` there
                $schemaID = $objectTypeResolver->resolveValue($object, new LeafField('__schema', null, [], [], $fieldDataAccessor->getField()->getLocation()), $objectTypeFieldResolutionFeedbackStore);
                if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
                    return $schemaID;
                }
                // Obtain the instance of the schema
                /** @var Schema[] */
                $schemaInstances = $this->getSchemaObjectTypeDataLoader()->getObjects([$schemaID]);
                $schema = $schemaInstances[0];
                return $schema->getTypeID($fieldDataAccessor->getValue('name'));
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
    /**
     * Since the return type is known for all the fields in this
     * FieldResolver, there's no need to validate them
     */
    public function validateResolvedFieldType(ObjectTypeResolverInterface $objectTypeResolver, FieldInterface $field) : bool
    {
        return \false;
    }
    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName) : ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case '__schema':
                return $this->getSchemaObjectTypeResolver();
            case '__type':
                return $this->getTypeObjectTypeResolver();
            default:
                return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
        }
    }
}

<?php

declare (strict_types=1);
namespace PoPSchema\SchemaCommons\FieldResolvers\ObjectType;

use PoPSchema\SchemaCommons\ObjectModels\AbstractTransientOperationPayload;
use PoPSchema\SchemaCommons\TypeResolvers\EnumType\OperationStatusEnumTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractTransientOperationPayloadObjectTypeResolver;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
/** @internal */
class TransientOperationPayloadObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    /**
     * @var \PoPSchema\SchemaCommons\TypeResolvers\EnumType\OperationStatusEnumTypeResolver|null
     */
    private $operationStatusEnumTypeResolver;
    protected final function getOperationStatusEnumTypeResolver() : OperationStatusEnumTypeResolver
    {
        if ($this->operationStatusEnumTypeResolver === null) {
            /** @var OperationStatusEnumTypeResolver */
            $operationStatusEnumTypeResolver = $this->instanceManager->getInstance(OperationStatusEnumTypeResolver::class);
            $this->operationStatusEnumTypeResolver = $operationStatusEnumTypeResolver;
        }
        return $this->operationStatusEnumTypeResolver;
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [AbstractTransientOperationPayloadObjectTypeResolver::class];
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        return ['status'];
    }
    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName) : ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'status':
                return $this->getOperationStatusEnumTypeResolver();
            default:
                return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
        }
    }
    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName) : int
    {
        switch ($fieldName) {
            case 'status':
                return SchemaTypeModifiers::NON_NULLABLE;
            default:
                return parent::getFieldTypeModifiers($objectTypeResolver, $fieldName);
        }
    }
    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'status':
                return $this->__('Status of the operation', 'schema-commons');
            default:
                return parent::getFieldDescription($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @return mixed
     */
    public function resolveValue(ObjectTypeResolverInterface $objectTypeResolver, object $object, FieldDataAccessorInterface $fieldDataAccessor, ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore)
    {
        /** @var AbstractTransientOperationPayload */
        $objectTransientOperationPayload = $object;
        /**
         * The parent already resolves all remaining fields
         */
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
}

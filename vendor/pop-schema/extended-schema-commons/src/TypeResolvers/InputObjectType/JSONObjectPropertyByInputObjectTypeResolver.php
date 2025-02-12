<?php

declare (strict_types=1);
namespace PoPSchema\ExtendedSchemaCommons\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractOneofInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
/** @internal */
class JSONObjectPropertyByInputObjectTypeResolver extends AbstractOneofInputObjectTypeResolver
{
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    protected final function getStringScalarTypeResolver() : StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }
    public function getTypeName() : string
    {
        return 'JSONObjectPropertyByInput';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Oneof input to navigate to some JSON object property, whether on the object\'s first level (via `key`) or deeper (via `path`)', 'extended-schema-commons');
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers() : array
    {
        return ['key' => $this->getStringScalarTypeResolver(), 'path' => $this->getStringScalarTypeResolver()];
    }
    public function getInputFieldDescription(string $inputFieldName) : ?string
    {
        switch ($inputFieldName) {
            case 'key':
                return $this->__('Query a property from the object\'s first level', 'extended-schema-commons');
            case 'path':
                return $this->__('Query a property on a deeper level of the object, using `.` to navigate the levels (eg: use "contact.email" to retrieve the value from `{ contact: { email: "hi@there.com" } }`)', 'extended-schema-commons');
            default:
                return parent::getInputFieldDescription($inputFieldName);
        }
    }
}

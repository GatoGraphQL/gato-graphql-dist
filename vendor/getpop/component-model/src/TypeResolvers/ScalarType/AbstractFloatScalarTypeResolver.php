<?php

declare (strict_types=1);
namespace PoP\ComponentModel\TypeResolvers\ScalarType;

use GatoExternalPrefixByGatoGraphQL\CastToType;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use stdClass;
/**
 * GraphQL Built-in Scalar
 *
 * @see https://spec.graphql.org/draft/#sec-Scalars.Built-in-Scalars
 * @internal
 */
abstract class AbstractFloatScalarTypeResolver extends \PoP\ComponentModel\TypeResolvers\ScalarType\AbstractScalarTypeResolver
{
    /**
     * @param string|int|float|bool|\stdClass $inputValue
     * @return string|int|float|bool|object|null
     */
    public function coerceValue($inputValue, AstInterface $astNode, ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore)
    {
        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();
        $this->validateIsNotStdClass($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return null;
        }
        /** @var string|int|float|bool $inputValue */
        $castInputValue = CastToType::_float($inputValue);
        if ($castInputValue === null) {
            $this->addDefaultError($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
            return null;
        }
        return (float) $castInputValue;
    }
}

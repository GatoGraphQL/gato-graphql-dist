<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace GatoExternalPrefixByGatoGraphQL\Symfony\Component\ExpressionLanguage;

/**
 * Represents a Token.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @internal
 */
class Token
{
    public $value;
    public $type;
    public $cursor;
    public const EOF_TYPE = 'end of expression';
    public const NAME_TYPE = 'name';
    public const NUMBER_TYPE = 'number';
    public const STRING_TYPE = 'string';
    public const OPERATOR_TYPE = 'operator';
    public const PUNCTUATION_TYPE = 'punctuation';
    /**
     * @param self::*_TYPE $type
     * @param int|null     $cursor The cursor position in the source
     * @param string|int|float|null $value
     */
    public function __construct(string $type, $value, ?int $cursor)
    {
        $this->type = $type;
        $this->value = $value;
        $this->cursor = $cursor;
    }
    /**
     * Returns a string representation of the token.
     */
    public function __toString() : string
    {
        return \sprintf('%3d %-11s %s', $this->cursor, \strtoupper($this->type), $this->value);
    }
    /**
     * Tests the current token for a type and/or a value.
     */
    public function test(string $type, ?string $value = null) : bool
    {
        return $this->type === $type && (null === $value || $this->value == $value);
    }
}

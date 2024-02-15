<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\DependencyInjection\Attribute;

use PrefixedByPoP\Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Exception\LogicException;
/**
 * An attribute to tell how a dependency is used and hint named autowiring aliases.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 * @internal
 */
#[\Attribute(\Attribute::TARGET_PARAMETER)]
final class Target
{
    /**
     * @var string|null
     */
    public $name;
    public function __construct(?string $name = null)
    {
        $this->name = $name;
    }
    public function getParsedName() : string
    {
        if (null === $this->name) {
            throw new LogicException(\sprintf('Cannot parse the name of a #[Target] attribute that has not been resolved. Did you forget to call "%s::parseName()"?', __CLASS__));
        }
        return \lcfirst(\str_replace(' ', '', \ucwords(\preg_replace('/[^a-zA-Z0-9\\x7f-\\xff]++/', ' ', $this->name))));
    }
    public static function parseName(\ReflectionParameter $parameter, ?self &$attribute = null, ?string &$parsedName = null) : string
    {
        $attribute = null;
        if (!($target = (\method_exists($parameter, 'getAttributes') ? $parameter->getAttributes(self::class) : [])[0] ?? null)) {
            $parsedName = (new self($parameter->name))->getParsedName();
            return $parameter->name;
        }
        $attribute = $target->newInstance();
        $name = $attribute->name = $attribute->name ?? $parameter->name;
        $parsedName = $attribute->getParsedName();
        if (!\preg_match('/^[a-zA-Z_\\x7f-\\xff]/', $parsedName)) {
            if (($function = $parameter->getDeclaringFunction()) instanceof \ReflectionMethod) {
                $function = $function->class . '::' . $function->name;
            } else {
                $function = $function->name;
            }
            throw new InvalidArgumentException(\sprintf('Invalid #[Target] name "%s" on parameter "$%s" of "%s()": the first character must be a letter.', $name, $parameter->name, $function));
        }
        return \preg_match('/^[a-zA-Z0-9_\\x7f-\\xff]++$/', $name) ? $name : $parsedName;
    }
}

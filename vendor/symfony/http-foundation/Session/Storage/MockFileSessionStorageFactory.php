<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace GatoExternalPrefixByGatoGraphQL\Symfony\Component\HttpFoundation\Session\Storage;

use GatoExternalPrefixByGatoGraphQL\Symfony\Component\HttpFoundation\Request;
// Help opcache.preload discover always-needed symbols
\class_exists(MockFileSessionStorage::class);
/**
 * @author Jérémy Derussé <jeremy@derusse.com>
 * @internal
 */
class MockFileSessionStorageFactory implements SessionStorageFactoryInterface
{
    /**
     * @var string|null
     */
    private $savePath;
    /**
     * @var string
     */
    private $name;
    /**
     * @var \Symfony\Component\HttpFoundation\Session\Storage\MetadataBag|null
     */
    private $metaBag;
    /**
     * @see MockFileSessionStorage constructor.
     */
    public function __construct(?string $savePath = null, string $name = 'MOCKSESSID', ?MetadataBag $metaBag = null)
    {
        $this->savePath = $savePath;
        $this->name = $name;
        $this->metaBag = $metaBag;
    }
    public function createStorage(?Request $request) : SessionStorageInterface
    {
        return new MockFileSessionStorage($this->savePath, $this->name, $this->metaBag);
    }
}

<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace GatoExternalPrefixByGatoGraphQL\Symfony\Component\HttpFoundation\RequestMatcher;

use GatoExternalPrefixByGatoGraphQL\Symfony\Component\HttpFoundation\IpUtils;
use GatoExternalPrefixByGatoGraphQL\Symfony\Component\HttpFoundation\Request;
use GatoExternalPrefixByGatoGraphQL\Symfony\Component\HttpFoundation\RequestMatcherInterface;
/**
 * Checks the client IP of a Request.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @internal
 */
class IpsRequestMatcher implements RequestMatcherInterface
{
    /**
     * @var mixed[]
     */
    private $ips;
    /**
     * @param string[]|string $ips A specific IP address or a range specified using IP/netmask like 192.168.1.0/24
     *                             Strings can contain a comma-delimited list of IPs/ranges
     */
    public function __construct($ips)
    {
        $this->ips = \array_reduce((array) $ips, static function (array $ips, string $ip) {
            return \array_merge($ips, \preg_split('/\\s*,\\s*/', $ip));
        }, []);
    }
    public function matches(Request $request) : bool
    {
        if (!$this->ips) {
            return \true;
        }
        return IpUtils::checkIp($request->getClientIp() ?? '', $this->ips);
    }
}

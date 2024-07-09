<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\Configuration;

/** @internal */
class MutationPayloadTypeOptions
{
    public const USE_PAYLOAD_TYPES_FOR_MUTATIONS = 'use-payload-types';
    public const USE_AND_QUERY_PAYLOAD_TYPES_FOR_MUTATIONS = 'use-and-query-payload-types';
    public const DO_NOT_USE_PAYLOAD_TYPES_FOR_MUTATIONS = 'do-not-use-payload-types';
}

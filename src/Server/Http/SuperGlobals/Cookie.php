<?php

declare(strict_types=1);

namespace Imi\Server\Http\SuperGlobals;

use Imi\Log\Log;
use Imi\RequestContext;

class Cookie implements \ArrayAccess, \JsonSerializable
{
    /**
     * @param int|string $offset
     * @param mixed      $value
     */
    public function offsetSet($offset, $value): void
    {
        Log::warning('imi does not support to assign values to $_COOKIE');
    }

    /**
     * @param int|string $offset
     */
    public function offsetExists($offset): bool
    {
        /** @var \Imi\Server\Http\Message\Request $request */
        $request = RequestContext::get('request');

        return null !== $request->getCookie($offset);
    }

    /**
     * @param int|string $offset
     */
    public function offsetUnset($offset): void
    {
        Log::warning('imi does not support to unset values from $_COOKIE');
    }

    /**
     * @param int|string $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        /** @var \Imi\Server\Http\Message\Request $request */
        $request = RequestContext::get('request');

        return $request->getCookie($offset);
    }

    public function __debugInfo(): array
    {
        return $this->jsonSerialize();
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize()
    {
        /** @var \Imi\Server\Http\Message\Request $request */
        $request = RequestContext::get('request');

        return $request->getCookieParams();
    }
}

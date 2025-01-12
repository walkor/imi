<?php

declare(strict_types=1);

namespace Imi\Test\Component\Tests;

use Imi\Util\File;
use Imi\Util\Imi;
use PHPUnit\Framework\Assert;

/**
 * @testdox Cache File2 Handler
 */
class CacheFile2Test extends BaseCacheTest
{
    protected string $cacheName = 'file2';

    public function testSetAndGet(): void
    {
        parent::testSetAndGet();
        $path = File::path(Imi::getNamespacePath('Imi\Test\Component\.runtime\cache'), sha1('imi'));
        Assert::assertTrue(is_file($path));
    }
}

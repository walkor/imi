<?php

require dirname(__DIR__, 4) . '/vendor/autoload.php';
require dirname(__DIR__) . '/vendor/autoload.php';

/**
 * 开启服务器.
 */
function startServer(): void
{
    // @phpstan-ignore-next-line
    function checkHttpServerStatus(): bool
    {
        $serverStarted = false;
        for ($i = 0; $i < 60; ++$i)
        {
            sleep(1);
            $context = stream_context_create(['http' => ['timeout' => 1]]);
            if ('imi' === @file_get_contents(imiGetEnv('HTTP_SERVER_HOST', 'http://127.0.0.1:13000/'), false, $context))
            {
                $serverStarted = true;
                break;
            }
        }

        return $serverStarted;
    }

    // @phpstan-ignore-next-line
    function checkChannelServerUtilServerStatus(): bool
    {
        $serverStarted = false;
        for ($i = 0; $i < 60; ++$i)
        {
            sleep(1);
            $context = stream_context_create(['http' => ['timeout' => 1]]);
            if ('imi' === @file_get_contents(imiGetEnv('HTTP_SERVER_HOST', 'http://127.0.0.1:13006/'), false, $context))
            {
                $serverStarted = true;
                break;
            }
        }

        return $serverStarted;
    }

    $servers = [
        'AppServer'    => [
            'start'         => __DIR__ . '/unit/AppServer/bin/start.sh',
            'stop'          => __DIR__ . '/unit/AppServer/bin/stop.sh',
            'checkStatus'   => 'checkHttpServerStatus',
        ],
        'ChannelServerUtilServer'    => [
            'start'         => __DIR__ . '/unit/ChannelServerUtilServer/bin/start.sh',
            'stop'          => __DIR__ . '/unit/ChannelServerUtilServer/bin/stop.sh',
            'checkStatus'   => 'checkChannelServerUtilServerStatus',
        ],
    ];

    foreach ($servers as $name => $options)
    {
        // start server
        $cmd = 'nohup ' . $options['start'] . ' > /dev/null 2>&1';
        echo "Starting {$name}...", \PHP_EOL;
        shell_exec("{$cmd}");

        register_shutdown_function(function () use ($name, $options) {
            // stop server
            $cmd = $options['stop'];
            echo "Stoping {$name}...", \PHP_EOL;
            shell_exec("{$cmd}");
            echo "{$name} stoped!", \PHP_EOL, \PHP_EOL;
        });

        // @phpstan-ignore-next-line
        if (($options['checkStatus'])())
        {
            echo "{$name} started!", \PHP_EOL;
        }
        else
        {
            throw new \RuntimeException("{$name} start failed");
        }
    }
}

startServer();
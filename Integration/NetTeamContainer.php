<?php

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

final class NetTeamContainer
{
    /**
     * @var Symfony\Component\HttpKernel\Kernel
     */
    private static $kernel;

    private static $appDir = NT3_APP_DIR;
    private static $kernelClass = NT3_KERNEL_CLASS;
    private static $env = NT3_ENV;
    private static $debug = NT3_DEBUG;
    private static $devController = '/app_dev.php';

    private static $alias = NT3_ALIAS;

    private static function getContainer()
    {
        if (null === self::$kernel) {
            self::loadKernel();
        }

        return self::$kernel->getContainer();
    }

    private static function loadKernel()
    {
        $bootstrapFile = self::$appDir . DIRECTORY_SEPARATOR . 'bootstrap.php.cache';
        $kernelFile = self::$appDir . DIRECTORY_SEPARATOR . self::$kernelClass . '.php';

        if (!file_exists($kernelFile)) {
            throw new Exception(sprintf('Unable to load Symfony Kernel from "%s"', $kernelFile));
        }

        require_once $bootstrapFile;
        require_once $kernelFile;

        self::$kernel = new self::$kernelClass(self::$env, self::$debug);
        self::$kernel->loadClassCache();
        self::$kernel->boot();

        $container = self::$kernel->getContainer();
        $container->enterScope('request');

        require_once __DIR__ . '/AliasRequest.php';

        $request = AliasRequest::createFromGlobals();
        $request->setAlias(self::$alias);
        $request->setEnv(self::$env);
        $request->setDevController(self::$devController);

        $container->set('request', $request, 'request');

        $dispatcher = $container->get('event_dispatcher');

        try {
            $event = new GetResponseEvent(self::$kernel, $request, HttpKernelInterface::MASTER_REQUEST);
            $dispatcher->dispatch(KernelEvents::REQUEST, $event);
        } catch (Exception $e) {}
    }

    public static function get($id)
    {
        return self::getContainer()->get($id);
    }

    public static function has($id)
    {
        return self::getContainer()->has($id);
    }

    public static function getParameter($name)
    {
        return self::getContainer()->getParameter($name);
    }

    public static function hasParameter($name)
    {
        return self::getContainer()->hasParameter($name);
    }
}
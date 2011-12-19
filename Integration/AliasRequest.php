<?php

use Symfony\Component\HttpFoundation\Request;

class AliasRequest extends Request
{
    const DEV_ENVIRONMENT = 'dev';

    protected $alias = null;

    protected $env = self::DEV_ENVIRONMENT;
    protected $devController = 'app_dev.php';

    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

    public function setEnv($env)
    {
        $this->env = $env;
    }

    public function setDevController($devController)
    {
        $this->devController = $devController;
    }

    protected function prepareBaseUrl()
    {
        $baseUrl = parent::prepareBaseUrl();

        if (null === $this->alias) {
            return $baseUrl;
        }

        if ($baseUrl == $this->alias) {
            return self::DEV_ENVIRONMENT === $this->env ? $this->devController : '';
        } else if (strpos($baseUrl, $this->alias) === 0) {
            $baseUrl = str_replace($this->alias, self::DEV_ENVIRONMENT === $this->env ? $this->devController . '/' : '/', $baseUrl);
        }

        return $baseUrl;
    }

    protected function prepareBasePath()
    {
        $basePath = parent::prepareBasePath();

        if (null === $this->devController) {
            return $basePath;
        }

        if ($basePath == $this->devController) {
            return '';
        } else if (strpos($basePath, $this->devController) === 0) {
            $basePath = str_replace($this->devController, '', $basePath);
        }

        return $basePath;
    }
}
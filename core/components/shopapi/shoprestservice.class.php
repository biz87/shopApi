<?php

require_once MODX_CORE_PATH . 'model/modx/rest/modrestservice.class.php';

class shopRestService extends modRestService
{
    /** {@inheritdoc} */
    protected function getController()
    {
        $expectedFile = trim($this->request->action, '/');
        $basePath = $this->getOption('basePath');
        $controllerClassPrefix = $this->getOption('controllerClassPrefix', 'modController');
        $controllerClassSeparator = $this->getOption('controllerClassSeparator', '_');
        $controllerClassFilePostfix = $this->getOption('controllerClassFilePostfix', '.php');

        /* handle [object]/[id] pathing */
        $expectedArray = explode('/', $expectedFile);
        $lastInt = 0;
        $paramName = '';
        foreach ($expectedArray as $k => $val) {
            if (is_numeric($val)) {
                if ($paramName) {
                    $this->request->parameters['urlParams'][$paramName] = $val;
                    $lastInt = $val;
                    unset($expectedArray[$k]);
                }
            } else {
                $paramName = $val;
                $lastInt = 0;
            }
        }
        $this->requestPrimaryKey = $lastInt;
        $expectedFile = implode('/', $expectedArray);
        if (empty($expectedArray)) $expectedArray = array(rtrim($expectedFile, '/') . '/');
        $id = array_pop($expectedArray);
        if (!file_exists($basePath . $expectedFile . $controllerClassFilePostfix) && intval($id) > 0) {
            $expectedFile = implode('/', $expectedArray);
            if (empty($expectedFile)) {
                $expectedFile = $id;
                $id = null;
            }
            $this->requestPrimaryKey = $id;
        }

        foreach ($this->iterateDirectories($basePath . '/*' . $controllerClassFilePostfix, GLOB_NOSORT) as $controller) {
            $controller = $basePath != '/' ? str_replace($basePath, '', $controller) : $controller;
            $controller = trim($controller, '/');
            $controllerFile = str_replace(array($controllerClassFilePostfix), array(''), $controller);
            $controllerClass = str_replace(array('/', $controllerClassFilePostfix), array($controllerClassSeparator, ''), $controller);
            if (strnatcasecmp($expectedFile, $controllerFile) == 0) {
                require_once $basePath . $controller;
                return $controllerClassPrefix . $controllerClassSeparator . $controllerClass;
            }
        }
        $this->modx->log(modX::LOG_LEVEL_INFO, 'Could not find expected controller: ' . $expectedFile);
        return null;
    }

    public function prepare()
    {
        parent::prepare();

        //add query properties to PUT method
        $this->request->parameters = array_merge(
            $this->request->parameters,
            $_GET
        );
    }
}
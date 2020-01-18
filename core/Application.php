<?php


namespace core;

use Exception;

class Application
{
    public function __construct()
    {
        // Set error and exception handler
        $this->setHandler();

        // Read Configuration files
        $this->readEnvironmentVar();

        // Detect Ajax Request
        $this->detectAjaxRequest();
    }

    /**
     * Send request
     *
     * @return bool|string
     * @throws Exception
     */
    public function send_request()
    {
        $request = $this->newRequest();

        return $request->send();
    }

    /**
     * Create new Request
     *
     * @return Request
     * @throws Exception
     */
    public function newRequest()
    {
        switch (Request::$req_options['endpoint']) {
            case 'query':
                return new Request(new TypeQuery);
                break;

            case 'series':
                return new Request(new TypeSeries);
                break;

            case 'labels':
                return new Request(new TypeLabels);
                break;

            default:
                throw new Exception("Incorrect endpoint");
        }
    }

    /**
     * Set Request parameters
     *
     * @param $array
     * @return void
     */
    public function setParams($array): void
    {
        Request::setParams($array);
    }

    /**
     * Read environment variables
     *
     * @return void
     */
    private function readEnvironmentVar(): void
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
        $dotenv->load();
    }

    /**
     * Detect Ajax request
     *
     * @throws Exception
     * @return void
     */
    private function detectAjaxRequest(): void
    {
        if (getenv('AJAX_DETECT')) {
            if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                throw new Exception('Request is not valid');
            }
        }
    }

    /**
     * Error and Exception handling
     *
     * @return void
     */
    private function setHandler(): void
    {
        error_reporting(E_ALL);
        set_error_handler('core\Error::errorHandler');
        set_exception_handler('core\Error::exceptionHandler');
    }
}
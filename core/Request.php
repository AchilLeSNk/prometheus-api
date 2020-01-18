<?php

namespace core;

class Request
{
    const LABELS = 'labels';
    const SERIES = 'series';
    const QUERY = 'query';

    public static $req_options = array();

    private $request;
    public function __construct(TypeOfRequest $request)
    {
        $this->request = $request;
    }

    /**
     * Send request
     *
     * @return bool|string
     */
    public function send()
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $this->request->url_format());
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        return curl_exec($curl);
    }

    /**
     * Set request params
     *
     * @param $array
     */
    public static function setParams($array) {
        foreach ($array as $key => $value) {
            Request::$req_options[$key] = $value;
        }
    }
}





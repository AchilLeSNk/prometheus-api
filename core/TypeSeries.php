<?php


namespace core;

use Exception;

class TypeSeries implements TypeOfRequest
{
    /**
     * @return string
     */
    public function url_format()
    {
        $url = getenv('PROMETHEUS_URL');

        $query = $this->parseRequestData();

        return "$url/api/v1/series?$query";
    }

    /**
     * @return string
     */
    public function parseRequestData()
    {
        $conditions = [];
        foreach (Request::$req_options as $key => $value) {
             if (!empty($value)) {
                $conditions[] = "$key=\"$value\"";
            }
        }

        return implode(',', $conditions);
    }
}
<?php


namespace core;


class TypeLabels implements TypeOfRequest
{
    /**
     * @return string
     */
    public function url_format()
    {
        $url = getenv('PROMETHEUS_URL');
        return "$url/api/v1/labels";
    }
}
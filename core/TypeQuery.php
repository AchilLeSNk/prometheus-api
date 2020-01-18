<?php


namespace core;


use Exception;

class TypeQuery implements TypeOfRequest
{
    /**
     * @return string
     */
    public function url_format()
    {
        $url = getenv('PROMETHEUS_URL');

        $query = $this->parseRequestData();

        return "$url/api/v1/query?query=$query";
    }

    /**
     * @return string
     * @throws Exception
     */
    public function parseRequestData()
    {
        $range_vector = (!empty(Request::$req_options['range_vector_selectors'])) ? "[" . Request::$req_options['range_vector_selectors'] . ":]" : '';
        if ($func_str = $this->parseFuncParams()) {
            return str_replace("{}", $this->parseIndexParams(), $func_str).$range_vector;
        } else {
            return $this->parseIndexParams().$range_vector;
        }
    }

    /**
     * @return string
     */
    public function parseFuncParams()
    {
        if (!empty(Request::$req_options['func'])) {
            return Request::$req_options['func']['name']."({}[".Request::$req_options['func']['offset_modifier']."])";
        }
    }

    /**
     * @return string
     * @throws Exception
     */
    public function parseIndexParams()
    {
        if (empty(Request::$req_options['index'])) {
            throw new Exception('Index is empty');
        }

        $conditions = [];
        $index_name = Request::$req_options['index']['name'];

        foreach (Request::$req_options['index']['conditions'] as $key => $value) {
            if (!empty($value)) {
                $conditions[] = "$key=\"$value\"";
            }
        }

        return $index_name . "{" . implode(',', $conditions) . "}";
    }
}
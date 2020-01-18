<?php

namespace core;

class Builder
{

    /*
     * The interval(seconds) between points on the chart for periods (1h - 1 hour, 1d - 1 day, etc.)
     * The Interval must be a multiple of 15, because Prometneus gives a value with an interval of 15 seconds
    */
    const INTERVAL = [
        '1h' => 30,
        '1d' => 120,
        '1w' => 840,
        '4w' => 2880,
        '1y' => 86400
    ];

    /**
     * Get Values from Prometheus response
     *
     * @param $response
     * @return array|null
     */
    public static function getValues($response)
    {
        $data = json_decode($response, true);

        if (empty($data['data']['result'])) {
            return null;
        }

        foreach ($data['data']['result'] as $datum) {
            foreach ($datum['values'] as $value) {
                $result_in[] = [
                    'date' => $value['0'],
                    'value' => round($value['1'] * 8)
                ];
            }
        }
        unset($data);

        return $result_in;
    }

    /**
     * Cleaning the array at a specified interval
     *
     * @param $array
     * @return array
     */
    public static function clear_array($array)
    {
        $result = [];
        $increment = static::INTERVAL[Request::$req_options['range_vector_selectors']] / 15;
        for ($i = 0; $i <= count($array); $i += $increment) {
            if (isset($array[$i])) {
                $result[] = $array[$i];
            }
        }
        return $result;
    }

    /**
     * Sort array by date
     *
     * @param $array
     * @return mixed
     */
    public static function sortArrByDate($array)
    {
        usort($array, function ($a, $b) {
            if ($a['date'] == $b['date']) {
                return 0;
            }
            return ($a['date'] < $b['date']) ? -1 : 1;
        });
        return $array;
    }

    /**
     * Get values with metric (kilo, mega, giga)
     *
     * @param $number
     * @return array|string
     */
    private static function formatNum($number) {
        $number = (int) $number;
        $sizes = array(0 => "Bytes", 1 => "KB", 2 => "MB", 3 => "GB");
        if ($number === 0) { return '0 Bytes'; }
        $i = floor(log((int)$number) / log(1000));
        return [round($number / pow(1000, $i), 2), $sizes[$i]];
    }
}
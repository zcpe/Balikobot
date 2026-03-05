<?php
declare(strict_types=1);

namespace Balikobot;

use \Throwable;
use \Exception;

/**
 * Implementation of data provider for REST application
 */
class Application
{
    /**
     * [Latitude, Longitude] per name of supported city
     */
    private const SUPPORTED_CITIES = [
        'Praha'     => [50.08, 14.44],
        'Brno'      => [49.20, 16.61],
        'Ostrava'   => [49.82, 18.26],
        'Olomouc'   => [49.59, 17.25],
        'Plzeň'     => [49.74, 13.37],
        'Plzen'     => [49.74, 13.37],
        'Pardubice' => [50.03, 15.78],
    ];

    /**
     * Run business-logic for input
     *
     * @throws Exception
     */
    public function getCityForecast(string $city): CityForecastDTO
    {
        if (!array_key_exists($city, self::SUPPORTED_CITIES)) {
            throw new Exception(
                sprintf('City (%s) is not supported', $city),
                422
            );
        }
        $latitude = self::SUPPORTED_CITIES[$city][0];
        $longitude = self::SUPPORTED_CITIES[$city][1];

        $openMeteoClient = new OpenMeteoClient();
        try {
            $cityForecast = new CityForecastDTO(
                $city,
                $openMeteoClient->get7DaysForecastForLocation($latitude, $longitude)
            );
        } catch (Throwable $t) {
            throw new Exception(
                sprintf('Service Unavailable (%s)', $t->getMessage()),
                503
            );
        }

        return $cityForecast;
    }
}

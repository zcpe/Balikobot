<?php
declare(strict_types=1);

namespace Balikobot;

use \RuntimeException;
use \JsonException;
use \DateMalformedStringException;
use \DateTimeImmutable;

/**
 * How to query API of https://open-meteo.com/ for weather data
 */
final class OpenMeteoClient
{
    private const API_ENPOINT_BASE = 'https://api.open-meteo.com/v1/forecast?';

    /**
     * How long is it allowed to wait for network response?
     */
    private readonly int $timeoutSeconds;

    public function __construct(int $timeoutSeconds = 30)
    {
        $this->timeoutSeconds = $timeoutSeconds;
    }

    /**
     * Return 7-days temperature forecast in °C for a place with given latitude & longitude
     *
     * @return LocationDateTemperatureDTO[]
     *
     * @throws RuntimeException
     * @throws JsonException
     * @throws DateMalformedStringException
     */
    public function get7DaysForecastForLocation(float $latitude, float $longitude): array
    {
        $apiEndpoint =
            self::API_ENPOINT_BASE
            .
            http_build_query([
                'latitude'  => round($latitude, 2),
                'longitude' => round($longitude, 2),
                'daily'     => 'temperature_2m_max,temperature_2m_min'
            ]);

        $streamContextOptions = [
            'http' => [
                'method'  => 'GET',
                'timeout' => $this->timeoutSeconds,
            ]
        ];
        $streamContext  = stream_context_create($streamContextOptions);
        $responseBody = file_get_contents($apiEndpoint, false, $streamContext);

        if (false === $responseBody || !strlen($responseBody)) {
            throw new RuntimeException(
                sprintf('No response from API after %d seconds', $apiEndpoint)
            );
        }

        $response = json_decode($responseBody, true, 512,JSON_THROW_ON_ERROR);

        $datesTemperatures = [];
        foreach ($response['daily']['time'] as $index => $time) {
            $datesTemperatures[] = new LocationDateTemperatureDTO(
                round(floatval($response['latitude']), 2),
                round(floatval($response['longitude']), 2),
                new DateTimeImmutable($time),
                round(floatval($response['daily']['temperature_2m_min'][$index]), 1),
                round(floatval($response['daily']['temperature_2m_max'][$index]), 1)
            );
        }

        return $datesTemperatures;
    }
}

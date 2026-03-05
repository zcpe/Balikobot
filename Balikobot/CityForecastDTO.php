<?php
declare(strict_types=1);

namespace Balikobot;

use \InvalidArgumentException;

/**
 * Data structure, representing forecast of temperature for city
 */
final class CityForecastDTO
{
    /**
     * Name of the city
     */
    private string $city;

    /**
     * List of temperatures range per day
     */
    private array $temperatures;

    public function __construct(string $city, array $datesTemperatures)
    {
        $this->city = $city;
        foreach ($datesTemperatures as $date) {
            if (!($date instanceof LocationDateTemperatureDTO)) {
                throw new InvalidArgumentException('Invalid DTO input for city forecast');
            }
            $this->temperatures[$date->getFormattedDate()] = $date;
        }
    }

    /**
     * Represent DTO as array
     */
    public function toArray(): array
    {
        $temperature = [];
        foreach ($this->temperatures as $t) {
            $temperature[] = [
                'date' => $t->getFormattedDate(),
                'min'  => $t->min,
                'max'  => $t->max,
            ];
        }

        return [
            'city'        => $this->city,
            'temperature' => $temperature,
        ];
    }
}

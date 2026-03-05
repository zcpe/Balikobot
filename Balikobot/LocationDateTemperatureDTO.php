<?php
declare(strict_types=1);

namespace Balikobot;

use \DateTimeImmutable;

/**
 * Value object, representing date & air temperature on location with certain coordinates
 */
final readonly class LocationDateTemperatureDTO
{
    /**
     * Coordinate of location
     */
    public float $latitude;

    /**
     * Coordinate of location
     */
    public float $longitude;

    /**
     * Date
     */
    public DateTimeImmutable $date;

    /**
     * Minimum value of temperature
     */
    public float $min;

    /**
     * Maximum value of temperature
     */
    public float $max;

    public function __construct(float $latitude, float $longitude, DateTimeImmutable $date, float $min, float $max)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->date = $date;
        $this->min = $min;
        $this->max = $max;
    }

    /**
     * Get date as formatted string
     */
    public function getFormattedDate(string $format = 'Y-m-d'): string
    {
        return $this->date->format($format);
    }
}

<?php

namespace App\Traits;

use Carbon\Carbon;

/**
 * DateHandler Trait
 *
 * This trait provides helper functions for handling dates using the Carbon library.
 * Author: Ali Monther
 */
trait DateHandler
{



    /**
     * Convert date to a different format.
     *
     * @param ?string $date
     * @param string $format
     * @return ?string
     */
    public function formatDate(?string $date, string $format = 'Y-m-d'): ?string
    {
        return $this->isValidDate($date) ?
            Carbon::parse($date)->format($format)
            : $date;
    }

    /**
     * Convert datetime to date only.
     *
     * @param ?string $datetime
     * @return ?string
     */
    public function dateFromDatetime(?string $datetime): ?string
    {
        return $this->isValidDate($datetime) ?
            Carbon::parse($datetime)->toDateString()
            : $datetime;
    }

    /**
     * Set time to 00:00:00 for a given date.
     *
     * @param ?string $date
     * @return ?string
     */
    public function startOfDay(?string $date): ?string
    {
        return $this->isValidDate($date)?
         Carbon::parse($date)->startOfDay()
            : $date;
    }

    /**
     * Check if a given date is in the past.
     *
     * @param ?string $date
     * @return bool
     */
    public function isPastDate(?string $date): bool
    {
        return $this->isValidDate($date) && Carbon::parse($date)->isPast();
    }

    /**
     * Check if a given date is in the future.
     *
     * @param ?string $date
     * @return bool
     */
    public function isFutureDate(?string $date): bool
    {
        return $this->isValidDate($date) && Carbon::parse($date)->isFuture();
    }

    /**
     * Add days to a given date.
     *
     * @param ?string $date
     * @param int $days
     * @return ?string
     */
    public function addDays(?string $date, int $days): ?string
    {
        return $this->isValidDate($date) ?
         Carbon::parse($date)->addDays($days)
            : $date;
    }

    /**
     * Check if a given string is a valid date.
     *
     * @param string|null $date
     * @return bool
     */
    public function isValidDate(?string $date): bool
    {
        try {
            Carbon::parse($date);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }



    /**
     * Get the formatted date with or without time.
     *
     * @param ?string $date
     * @param bool $withTime
     * @param bool $isMobile
     * @return ?string
     */
    public function getFormattedDate(?string $date, bool $withTime = false, bool $isMobile = false): ?string
    {
        return $this->isValidDate($date) ?
            $this->formatDate($date, $this->getDateFormat($withTime,$isMobile)):
            $date;
    }

    /**
     * Get the date format from the Laravel configuration.
     *
     * @param bool $withTime
     * @param bool $isMobile
     * @return string
     */
    private function getDateFormat(bool $withTime = false, bool $isMobile= false): string
    {
        return $isMobile ? $this->getMobileFormat($withTime) : $this->getWebFormat($withTime);
    }

    private function getWebFormat(bool $withTime = false): string
    {
        return $withTime ? config('app.date_time_format') : config('app.date_format');
    }
    private function getMobileFormat(bool $withTime = false): mixed
    {
        return $withTime ? config('app.mobile_date_time_format') : config('app.mobile_date_format');
    }

    public function getFormat($dateString)
    {
        if (strpos($dateString, '-') !== false) {
            $separator = '-';
        } elseif (strpos($dateString, '/') !== false) {
            $separator = '/';
        } elseif (strpos($dateString, '.') !== false) {
            $separator = '.';
        } else {
            return 'Unknown format';
        }

        // Split the date string into parts
        $parts = explode($separator, $dateString);

        if (count($parts) !== 3) {
            return 'Unknown format';
        }

        list($part1, $part2, $part3) = $parts;

        // Determine the format based on the length and value of the parts
        if (strlen($part1) === 4) {
            // Assuming the year is in the first part
            if (checkdate($part2, $part3, $part1)) {
                return 'Y' . $separator . 'm' . $separator . 'd';
            }
        } elseif (strlen($part3) === 4) {
            // Assuming the year is in the last part
            if (checkdate($part2, $part1, $part3)) {
                return 'd' . $separator . 'm' . $separator . 'Y';
            }
        } elseif (strlen($part2) === 4) {
            // Assuming the year is in the middle part
            if (checkdate($part1, $part3, $part2)) {
                return 'm' . $separator . 'd' . $separator . 'Y';
            }
        }

        return 'Unknown format';
    }


      /**
     * Calculate the difference between two dates in days and hours.
     *
     * @param string $startDate
     * @param string $endDate
     * @return string
     */
    public function calculateDateDifference($startDate, $endDate)
    {
           // Check if both dates are provided and not null
        if (is_null($startDate) || is_null($endDate)) {
            $days = 0;
            $hours = 0;
    
        }

        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        
        $days = $start->diffInDays($end);
        $hours = $start->copy()->addDays($days)->diffInHours($end);

        if($days > 0)
            return $days.' Days, '. $hours.' Hours ago';
        return $hours.' Hours ago';
    }
}

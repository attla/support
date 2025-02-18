<?php

namespace Attla\Support\Laravel\Concerns;

trait HasUTCDates
{
    /**
     * Get the format for database stored dates
     *
     * @return string
     */
    public function getDateFormat()
    {
        return 'Y-m-d\TH:i:s';
    }

    /**
     * Customize date serialization
     *
     * @param \DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format($this->getDateFormat());
    }
}

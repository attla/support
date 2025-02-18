<?php

namespace Attla\Support\Laravel\Concerns;

trait HasIntDates
{
    /**
     * Boot int dates
     *
     * @return void
     */
    public static function bootHasIntDates()
    {
        static::building(function ($model) {
            $model->casts = array_merge($model->casts, [
                static::CREATED_AT => 'timestamp',
                static::UPDATED_AT => 'timestamp',
            ]);
        });
    }

    /**
     * Get the format for database stored dates
     *
     * @return string
     */
    public function getDateFormat()
    {
        return 'U';
    }

    /**
     * Customize date serialization
     *
     * @param \DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->getTimestamp();
    }
}

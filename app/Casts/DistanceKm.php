<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Facades\Log;

class DistanceKm implements CastsAttributes
{
	/**
	 * Cast the given value.
	 *
	 * @param  \Illuminate\Database\Eloquent\Model  $model
	 * @param  string $key
	 * @param  mixed  $value
	 * @param  array  $attributes
	 * @return array
	 */
	public function get($model, $key, $value, $attributes)
	{
		$strValue = $value < 1
			? round($value * 1000, 0).'m'
			: round($value, 0).'km';

		return $strValue;
	}

	/**
	 * Prepare the given value for storage.
	 *
	 * @param  \Illuminate\Database\Eloquent\Model  $model
	 * @param  string $key
	 * @param  array  $value
	 * @param  array  $attributes
	 * @return mixed
	 */
	public function set($model, $key, $value, $attributes)
	{
		// do nothing
		return $value;
	}
}
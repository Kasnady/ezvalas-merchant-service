<?php

namespace App\Http\Controllers;

use App\Exceptions\InputDataInsufficientException;
use App\Models\Merchant as MerchantModel;
use App\Traits\Merchants;
use Illuminate\Support\Facades\Validator;
use Log;

class MerchantController extends Controller
{
	use Merchants;

	private static $_instance;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Initiate Class
	 *
	 * @return self
	 */
	public static function getInstance()
	{
		if (!(self::$_instance instanceof self))
		{
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Get Merchants nearby provided Latitude and Longitude
	 *
	 * @param  float $latitude
	 * @param  float $longitude
	 * @param  int   $range
	 * @param  int   $limit
	 * @return array
	 */
	protected function getNearbyThisGeoLocation(
		float $latitude, float $longitude, $range=2, $limit=10
	) {
		$MerchantModel = new MerchantModel();
		return $MerchantModel->getNearbyThisGeoLocation($latitude, $longitude, $range, $limit);
	}

	/**
	 * Get a validator for an incoming request.
	 *
	 * @param  array  $request
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(array $request, $strict=true)
	{
		if (empty($request)) {
			throw new InputDataInsufficientException($request);
		}

		$rules = [
			'curLat'=> 'required|numeric',
			'curLong'=> 'required|numeric',
			'limit'=> 'numeric|min:1',
			'range'=> 'numeric'
		];

		// Filter out field not in $request by Key if not strict
		if (!$strict) {
			$rules = array_filter($rules, function($rule) use ($request) {
				$thisKey = key((array) $rule);
				return array_search($thisKey, array_keys($request));
			});
		}

		return Validator::make($request, $rules);
	}
}

<?php

namespace App\Traits;

use App\Statics\ResponseCode;
use App\Traits\ApiResponser;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Log;

trait Merchants
{
	use ApiResponser;

	/**
	 * Get Merchants near provided geo location
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @return mixed
	 */
	public function getNearbyGeolocation(Request $request)
	{
		$validateResult = $this->validator($request->all(), false);
		// is Validation Failed
		if ($validateResult->fails()) {
			Log::info("Validation Failed");
			Log::info($validateResult->errors());
			return $this->successResponse($validateResult,
				ResponseCode::GET_NEARBY_MERCHANTS_VALIDATION_FAILED);
		}

		try {
			$result = $this->getNearbyThisGeoLocation(
				$request->curLat, $request->curLong, $request->range, $request->limit
			);
			return $this->successResponse($result, ResponseCode::GET_NEARBY_MERCHANTS_SUCCESS);
		} catch (\Exception $e) {
			Log::error('Exception'. $e);
			return $this->successResponse($e->getMessage(), ResponseCode::GET_NEARBY_MERCHANTS_ERROR);
		}
	}
}
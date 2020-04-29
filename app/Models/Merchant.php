<?php

namespace App\Models;

use App\Casts\DistanceKm;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Merchant extends Model
{
	protected $primaryKey = 'user_id';
	public $incrementing = false;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [ ];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [ ];

	/**
	 * Get Merchants nearby provided Latitude and Longitude
	 *
	 * @param  float $latitude
	 * @param  float $longitude
	 * @param  int   $range
	 * @param  int   $limit
	 * @return array
	 */
	public function getNearbyThisGeoLocation(
		float $latitude, float $longitude, $range=2, $limit=10, bool $byKm=true)
	{
		$range = empty($range) ? 2 : $range;
		$limit = empty($limit) ? 10 : $limit;

		/**
		 * Formula refer:
		 * https://stackoverflow.com/a/41353948/2138727
		 *
		 */

		$earthRadius = $byKm ? 6371 : 3959; // 6371: KM, 3959: Miles

		return $this->select('user_id', 'store_name', 'address', 'latitude', 'longitude')
			->selectRaw(
				"( $earthRadius *
					acos(
						cos( radians( $latitude ) ) * cos( radians( latitude ) )
						*
						cos( radians( longitude ) - radians( $longitude ) )
						+
						sin( radians( $latitude ) ) * sin( radians( latitude ) )
					)
				) as distance"
			)->having('distance', '<', $range)
			->orderBy('distance')
			->limit($limit)
			->withCasts([
				'distance' => DistanceKm::class
			])
			->get();
	}

	/**
	 * Relations
	 */
	public function User() {
		return $this->belongsTo(User::class, 'user_id', 'id');
	}

	public function MerchantSetting() {
		return $this->hasOne(MerchantSetting::class, 'merchant_id', 'user_id');
	}
}

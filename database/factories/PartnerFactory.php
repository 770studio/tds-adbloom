<?php

namespace Database\Factories;

use App\Models\Partner;
use App\Models\RedirectStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PartnerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Partner::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'send_pending_status' => RedirectStatus::fakeSendPendingStatus(),
            'external_id' => $this->faker->numberBetween(5555,6666),
            'name' => $this->faker->name(),
            'pending_url' => 'http://parner.com/?var1={eventId}&date={date}&var3={datetime}&var4={dateUpdated}&var5={datetimeUpdated}&var5={name}&var6={opportunityId}&var7={currency}&var8={payout}&var9={userPayout}&var10={points}&var11={status}&var12={token}',
            'send_pending_postback' => 1,
            'pending_timeout' => 1,
            'rev_share' => 1,
            'percentage' => 10,
            'convert_to_points' => 1,
            'points_multiplier' => 1200,
        ];
    }
}

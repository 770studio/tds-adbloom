<?php

namespace Tests\Feature;

use App\Events\ConversionUpdatingEvent;
use App\Models\Conversion;
use App\Models\Partner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

//use Illuminate\Foundation\Testing\DatabaseMigrations;


class RevShareTest extends TestCase
{
   // use MigrateFreshSeedOnce;//  RefreshDatabase; //, DatabaseMigrations;
        use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_we_are_under_testing_env(): void
    {

        $this->assertSame("testing", $this->app->environment());

        if ($this->hasFailed()) {
            dd('wrong env');
            // $this->seed();
        }
        //$this->assertTrue("nova_test" == $this->getConnection()->getDatabaseName());

    }

    /**
     * Test
     */
    public function test_partner_can_be_created()
    {  // dd(Partner::all());
        //dd(DB::getDefaultConnection(), DB::table('partners')->get());
        $partner = Partner::factory()->create([
            'rev_share' => 1,
            'percentage' => 10,
            'points_multiplier' => 1253
        ]);

        $this->assertDatabaseHas('partners', [
            'short_id' => $partner->short_id
        ]);

        return $partner;


    }

    /**
     * Test ConversionUpdatingEvent dispatching.
     * @depends test_partner_can_be_created
     */
    public function test_conversion_creating_dispatches_the_event()
    {
        $partner = Partner::factory()->create([
            'rev_share' => 1,
            'percentage' => 10,
            'points_multiplier' => 1253
        ]);

        Event::fake();
        $conversion = Conversion::factory()
            ->for($partner)
            ->create(['Stat_payout' => 100]);

        $this->assertDatabaseHas('conversions', [
            'id' => $conversion->id
        ]);
        Event::assertDispatched(ConversionUpdatingEvent::class);
        $this->assertTrue($conversion->wasRecentlyCreated);
        return $conversion;
    }

    /**
     * Test ConversionUpdatingEvent dispatching.
     * @depends test_conversion_creating_dispatches_the_event
     *
     */
    public function test_conversion_updating_dispatches_the_event($conversion)
    {

        // $conversion = Conversion::find($conversion->id); // $conversion->refresh();

        Event::fake();
        $conversion->Stat_payout = 157.75;
        $conversion->save();

        Event::assertDispatched(ConversionUpdatingEvent::class);

        //dd($conversion->getChanges());
        $this->assertArrayHasKey("Stat_payout", $conversion->getChanges());
        //$this->assertTrue($conversion->wasRecentlyCreated);

        return $conversion;

    }
    /**
     * Test recalculate userPayout  .
     *  E.g. if the payout value is $1 and RevShare is equal to 70%,
     * a calculated userPayout value should be $1*70%=$0.7
     *
     * @depends test_conversion_creating_dispatches_the_event
     * @depends test_partner_can_be_created
     */
    public function test_user_payout_and_points_are_recalculated(): void
    {
        $partner = Partner::factory()->create([
            'rev_share' => 1,
            'percentage' => 10,
            'points_multiplier' => 1253
        ]);

        $conversion = Conversion::factory()
            ->for($partner)
            ->create(['Stat_payout' => 100]);

        $conversion->Stat_payout = 15;
        $conversion->save();

        $this->assertEquals(15 * 0.1, $conversion->user_payout);
        $this->assertEquals(floor(1.5 * $partner->points_multiplier), $conversion->user_points);






    }
}

<?php

namespace App\Jobs;

use App\Models\Conversion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class doPartnerPostBack implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Conversion
     */
    private $conversion;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Conversion $conversion)
    {
        $this->conversion = $conversion;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(!$this->conversion->Partner || !$this->conversion->Opportunity) return;
        if(!$this->conversion->Partner->send_pending_postback ) return;

//http://parner.com/?var1={eventId}&date={date}&var3={datetime}&var4={dateUpdated}&var5={datetimeUpdated}&var5={name}&var6={opportunityId}&var7={currency}&var8={payout}&var9={userPayout}&var10={points}&var11={status}&var12={token}



        /*        eventId = Stat tune event id
date = Created (just date)
datetime = Created
dateUpdated = Updated (just date)
datetimeUpdated = Updated
name = Opportunity Name (matched via external ID)
opportunityId = (opportunity ID matched via external ID)
currency = Stat currency
payout = Stat Payout
userPayout = FIXED FOR NOW
                       points = FIXED FOR NOW
                                          status = depends or Partner settings
    token = HARD CODED*/

        $url = $this->conversion->Partner->pending_url;
              doPostBackJob::dispatch(
                  $url
              );
    }
}

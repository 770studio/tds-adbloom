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

        $url = $this->conversion->Partner->pending_url;
              doPostBackJob::dispatch(
                  $url
              );
    }
}

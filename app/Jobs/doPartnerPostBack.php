<?php

namespace App\Jobs;

use App\Models\Conversion;
use App\Models\Infrastructure\PartnerPostback;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;

class doPartnerPostBack implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;
    /**
     * The maximum number of unhandled exceptions to allow before failing.
     *
     * @var int
     */
    public $maxExceptions = 2;
    /**
     * @var Conversion
     */
    private $conversion;
    /**
     * @var bool
     */
    private $secondary;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Conversion $conversion, $secondary = false)
    {
        $this->conversion = $conversion;
        $this->secondary = $secondary;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle(PartnerPostback $pb): void
    {
        $pb->setConversion($this->conversion)
            ->setSecondary($this->secondary)
            ->send();

    }



    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array
     */
    public function tags()
    {
        return [
            sprintf('doPartnerPostBack parnerId:%s conversionId:%s %s',
                $this->conversion->Partner->external_id,
                $this->conversion->id,
                app()->environment()
            ),
            $this->conversion->Stat_tune_event_id,
            'conversion:' . $this->conversion->id,
            $this->conversion->Partner->external_id,
            $this->conversion->Partner->name,
            app()->environment(),
        ];
    }


    /**
     * Get the middleware the job should pass through.
     *
     * @return array
     */
    public function middleware()
    {
        return [new WithoutOverlapping($this->conversion->id)];
    }

    /**
     * The unique ID of the job.
     *
     * @return string
     */
    public function uniqueId()
    {
        return (string)$this->conversion->id;
    }


    /**
     * Calculate the number of seconds to wait before retrying the job.
     *
     * @return int
     */
    public function backoff()
    {
        return 30;
    }

}

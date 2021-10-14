<?php


namespace App\Jobs\Middleware;

use Illuminate\Support\Facades\Redis;

/**
 * Class TuneAPIRateLimited
 * @package App\Jobs\Middleware
 *
 * Networks are limited to a maximum of 50 API calls every 10 seconds.
 * If you exceed the rate limit, your API call returns the following error: "API usage exceeded rate limit. Configured: 50/10s window; Your usage: " followed by the number of API calls you've attempted in that 10 second window.
 */
class TuneAPIRateLimited
{
    /**
     * Process the queued job.
     *
     * @param mixed $job
     * @param callable $next
     * @return mixed
     */
    public function handle($job, $next)
    {
        if (config('queue.default') === 'sync' || app()->runningUnitTests()) {
            $next($job);
            return;
        }

        Redis::throttle(
            config('services.tune_api.network_id')
        )->block(10)->allow(15)->every(10)
            ->then(function () use ($job, $next) {
                // Lock obtained...

                $next($job);
            }, function () use ($job) {
                // Could not obtain lock...

                $job->release(60);
            });
    }
}

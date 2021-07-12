<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Tune\AdvertiserApi;
use Tune\Utils\HttpQueryBuilder;
use Tune\Utils\Operator;


class ConversionsUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'conversions:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws Exception
     */
    public function handle(AdvertiserApi $api)
    {

        print_r($api->report()->getConversions(function (HttpQueryBuilder $builder) {
            return $builder->setFields([
                'Browser.id',
                'Browser.display_name',
                'OfferUrl.preview_url',
                'Offer.name'
            ])->addFilter('Stat.datetime', [
                '2019-12-19 00:00:00',
                '2020-12-19 00:00:00'
            ], null, Operator::BETWEEN);
        }, /* Request options */ []));

    }
}

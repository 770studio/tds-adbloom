<?php

namespace Tests\Unit;

use App\Interfaces\DaliaPublisherAPIServiceIF;
use Tests\TestCase;

class DaliaPublisherAPIServiceTest extends TestCase
{
    public function test_we_are_under_testing_env()
    {
        $this->assertTrue("testing" == $this->app->environment());
        //$this->assertTrue("nova_test" == $this->getConnection()->getDatabaseName());

    }

    public function test_get_data()
    {
        $daliaApi = $this->app->make(DaliaPublisherAPIServiceIF::class);
        $daliaApi->getAll();
dd($daliaApi);
    }
}

<?php

namespace Tests\Unit;

use App\Interfaces\DaliaPublisherAPIServiceIF;
use Illuminate\Contracts\Container\BindingResolutionException;
use Tests\TestCase;

class DaliaPublisherAPIServiceTest extends TestCase
{
    public function test_we_are_under_testing_env(): void
    {
        $this->assertSame("testing", $this->app->environment());
        //$this->assertTrue("nova_test" == $this->getConnection()->getDatabaseName());

    }

    /**
     * @throws BindingResolutionException
     */
    /*   public function test_get_data(): void
      {
         $daliaApi = $this->app->make(DaliaPublisherAPIServiceIF::class);
          $daliaApi->getAll();
          dd($daliaApi);
    } */
}

<?php

namespace Tests\Feature;

use Database\Seeders\DomainSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DomainChecksTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     *
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DomainSeeder::class);
    }

    public function testDomainsCheck()
    {
        $randExistingDomain =  DB::table('domains')->inRandomOrder()->first();
        $id = $randExistingDomain->id;

        $fakeHtml = file_get_contents(realpath(implode(DIRECTORY_SEPARATOR, [__DIR__,'..','fixtures','test.html'])));
        $domainData = [
            'domain_id' => $id,
            'status_code' => 200,
            'keywords' => 'keyword1 keyword2',
            'description' => 'This is test description',
        ];

        Http::fake([$randExistingDomain->name => Http::response($fakeHtml, 200)]);

        $response = $this->post(route('domains.check', $id));

        $response->assertRedirect(route('domains.show', ['id' => $randExistingDomain->id]));
        $this->assertDatabaseHas('domain_checks', $domainData);
    }
}

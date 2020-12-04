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
    protected $domainId;
    protected $domainData;
    protected function setUp(): void
    {
        parent::setUp();
        $dataStamp = '2020-12-04 19:11:54';
        $this->domainData = ['name' => 'https://www.example.ru','updated_at' => $dataStamp, 'created_at' => $dataStamp];
        $this->domainId = DB::table('domains')->insertGetId($this->domainData);
    }

    public function testDomainsCheck()
    {

        $fakeHtml = file_get_contents(realpath(implode(DIRECTORY_SEPARATOR, [__DIR__,'..','fixtures','test.html'])));
        $domainData = [
            'domain_id' => $this->domainId,
            'status_code' => 200,
            'keywords' => 'keyword1 keyword2',
            'description' => 'This is test description',
        ];

        Http::fake([$this->domainData['name'] => Http::response($fakeHtml, 200)]);

        $response = $this->post(route('domains.check', $this->domainId));

        $response->assertRedirect(route('domains.show', ['id' => $this->domainId]));
        $this->assertDatabaseHas('domain_checks', $domainData);
    }
}

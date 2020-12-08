<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DomainsTest extends TestCase
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
        $this->domainData = ['name' => 'https://www.example.ru'];
        $this->domainId = DB::table('domains')->insertGetId($this->domainData);
    }

    public function testHomepage()
    {
        $response = $this->get(route('homepage'));
        $response->assertStatus(200);
    }

    public function testDomainStore()
    {
        $incorrectUrl = 'asdd';
        $response = $this->post(route('domains.store'), ['domain' => ['name' => $incorrectUrl]]);
        $response->assertRedirect(route('homepage'));
        $response->assertSessionHasErrors();

        $domainData = ['name' => 'https://example@gmail.com'];

        $response = $this->post(route('domains.store'), ['domain' => $domainData]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $normalizedDomainData = ['name' => normalizeUrl($domainData['name'])];
        $this->assertDatabaseHas('domains', ['name' => $normalizedDomainData]);
    }

    public function testDomainsShow()
    {
        $response = $this->get(route('domains.show', $this->domainId));
        $response->assertStatus(200);
        $response->assertSee($this->domainData['name']);
    }

    public function testDomainsIndex()
    {
        $response = $this->get(route('domains.index'));
        $response->assertSee($this->domainData);
    }
}

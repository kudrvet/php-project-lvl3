<?php

namespace Tests\Feature;

use Database\Seeders\DomainSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DomainsTest extends TestCase
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
        $randExistingDomain =  DB::table('domains')->inRandomOrder()->first();
        $response = $this->get(route('domains.show', $randExistingDomain->id));
        $response->assertStatus(200);

        $response->assertSee($randExistingDomain->name);
    }

    public function testDomainsIndex()
    {
        $latestChecks = DB::table('domain_checks')
            ->select('domain_id', DB::raw('MAX(created_at) as last_post_created_at'))
            ->groupBy('domain_id');

        $lastChecksWithStatus = DB::table('domain_checks')
            ->JoinSub($latestChecks, 'latest_checks', function ($join) {
                $join->on('domain_checks.created_at', '=', 'latest_checks.last_post_created_at');
            })
            ->select('latest_checks.domain_id', 'latest_checks.last_post_created_at', 'domain_checks.status_code');

        $domainsWithLastCheck = DB::table('domains')
            ->leftjoinSub($lastChecksWithStatus, 'latest_checks', function ($join) {
                $join->on('domains.id', '=', 'latest_checks.domain_id');
            })
            ->select('domains.id', 'domains.name', 'latest_checks.status_code', 'latest_checks.last_post_created_at')
            ->get();

         $response = $this->get(route('domains.index'));

        foreach ($domainsWithLastCheck as $domain) {
            $response->assertSee($domain->name);
        }
    }
}

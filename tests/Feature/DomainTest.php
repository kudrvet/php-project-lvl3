<?php

namespace Tests\Feature;

use App\Models\Domain;
use Database\Seeders\DomainSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

use Tests\TestCase;

class DomainsTest extends TestCase

{
//    use DatabaseTransactions;
//    use DatabaseMigrations;
    use RefreshDatabase;
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
//        Domain::factory()->count(2)->create();
    }

    public function testHomepage()
    {
        $response = $this->get(route('homepage'));

        $response->assertStatus(200);
    }

    public function testDomainStoreInvalidInput()
    {
        $incorrectData = 'asdd';
        $response = $this->post(route('domains.store'),['domain'=> ['name' => $incorrectData]]);
        $response->assertRedirect(route('homepage'));
        $response->assertSessionHasErrors();

    }

    public function testDomainStoreExistingDomain()
    {
        $domainName = "https://www.kinopoisk.ru";
        $response = $this->post(route('domains.store'),['domain'=> ['name' => $domainName]]);
        $response->assertRedirect();

        $this->assertDatabaseHas('domains', [
            'name' => $domainName,
        ]);

        $domainFromDB = DB::table('domains')->select('*')
            ->where('name','=',$domainName)->get()->toArray()[0];
        $this->assertTrue($domainFromDB->updated_at !== $domainFromDB->created_at);

    }

    public function testDomainStoreNewDomain()
    {
        $factoryData = Domain::factory()->make()->toArray();
        $factoryName = $factoryData['name'];

        $response = $this->post(route('domains.store'),['domain'=> ['name' => $factoryName]]);
        $urlParts = parse_url($factoryName);
        $normalizedName="{$urlParts['scheme']}://{$urlParts['host']}";
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('domains', ['name'=> $normalizedName]);

    }

    public function testDomainsShow()
    {
       $randExistingId =  DB::table('domains')->inRandomOrder()->first()->id;
       $response = $this->get(route('domains.show',['id'=>$randExistingId]));
       $response->assertStatus(200);

       $domain = DB::table('domains')->find($randExistingId);

       $body = $response->getContent();
       $this->assertStringContainsString($domain->id, $body);
       $this->assertStringContainsString($domain->name, $body);
       $this->assertStringContainsString($domain->updated_at, $body);
       $this->assertStringContainsString($domain->created_at, $body);

    }

    public function testDomainsIndex()
    {
        $body = $this->get(route('domains.index'))->getContent();
        $domains = (DB::table('domains')->get()->toArray());

        foreach($domains as $domain) {
            $this->assertStringContainsString($domain->id, $body);
            $this->assertStringContainsString($domain->name, $body);
            $this->assertStringContainsString($domain->updated_at, $body);
            $this->assertStringContainsString($domain->created_at, $body);
        }
    }
}

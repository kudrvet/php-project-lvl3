<?php

namespace Tests\Feature;

use App\Models\Domain;
use App\Models\DomainCheck;
use Database\Seeders\DomainSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

use function PHPUnit\Framework\assertTrue;
use Tests\TestCase;

class DomainsTest extends TestCase

{
//    use DatabaseTransactions;
//    use DatabaseMigrations;
//    use RefreshDatabase;
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
//        DB::table('domains')->insert([
//            ['http://yandex.ru','2020-11-23 22:38:34','2020-11-23 22:38:34'],
//            ['http://vc.ru','2020-12-23 22:38:34','2020-12-23 22:38:34'],
//        ]);

//        Domain::factory()->count(5)->create()->each(function ($domain) {
//            DomainCheck::factory()->count(3)->create(['domain_id'=>$domain->id]);
//        });

    }

    public function testHomepage()
    {
        $response = $this->get(route('homepage'));

        $response->assertStatus(200);
    }

    public function testDomainStore()
    {
        //invalid input
        $incorrectUrls = ['asdd','12341','yandex.ru'];
        foreach($incorrectUrls as $incorrectUrl) {
            $response = $this->post(route('domains.store'),['domain'=> ['name' => $incorrectUrl]]);
            $response->assertRedirect(route('homepage'));
        }

        //existingDomain
//        $randomExistingDomainName =  DB::table('domains')->inRandomOrder()->first()->name;
//        dd(DB::table('domains')->get());
//        print_r($randomExistingDomainName);
//        $response = $this->post(route('domains.store'),['domain'=> ['name' => $randomExistingDomainName]]);
//        $response->assertRedirect();
//
//        $domainFromDB = DB::table('domains')->select('*')
//            ->where('name','=',$randomExistingDomainName)->get()->toArray()[0];
//        $this->assertTrue($domainFromDB->updated_at !== $domainFromDB->created_at);

        //newDomain
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
       $randExistingDomain =  DB::table('domains')->inRandomOrder()->first();
       $response = $this->get(route('domains.show',$randExistingDomain->id));
       $response->assertStatus(200);


       $body = $response->getContent();

       $this->assertStringContainsString($randExistingDomain->id, $body);
       $this->assertStringContainsString($randExistingDomain->name, $body);
       $this->assertStringContainsString($randExistingDomain->updated_at, $body);
       $this->assertStringContainsString($randExistingDomain->created_at, $body);


       $domainsChecks = DB::table('domain_checks')
            ->where('domain_id','=',$randExistingDomain->id)
            ->orderByDesc('created_at')
            ->get();

       foreach($domainsChecks as $domainCheck) {
            $this->assertStringContainsString($domainCheck->id, $body);
            $this->assertStringContainsString($domainCheck->status_code ?? '', $body);
            $this->assertStringContainsString($domainCheck->h1 ?? '', $body);
            $this->assertStringContainsString($domainCheck->keywords ?? '', $body);
            $this->assertStringContainsString($domainCheck->description ?? '', $body);
            $this->assertStringContainsString($domainCheck->created_at, $body);
        }

    }

    public function testDomainsIndex()
    {

        $latestChecks = DB::table('domain_checks')
            ->select('domain_id','status_code',DB::raw('MAX(created_at) as last_post_created_at'))
            ->groupBy('domain_id');

        $domainsWithLastCheck = DB::table('domains')
            ->joinSub($latestChecks, 'latest_checks', function ($join) {
                $join->on('domains.id', '=', 'latest_checks.domain_id');
            })
            ->select('latest_checks.domain_id','domains.name','latest_checks.status_code','latest_checks.last_post_created_at')
            ->get();

//        $body = $this->get(route('domains.index'))->getContent();
//        foreach($domainsWithLastCheck as $domain) {
//            $this->assertStringContainsString($domain->domain_id, $body);
//            $this->assertStringContainsString($domain->name, $body);
//            $this->assertStringContainsString($domain->last_post_created_at, $body);
//            $this->assertStringContainsString($domain->status_code ?? '', $body);
//        }

        $response = $this->get(route('domains.index'));

        foreach($domainsWithLastCheck as $domain) {
            $response->assertSee($domain->domain_id);
            $response->assertSee($domain->name);
            $response->assertSee($domain->last_post_created_at);
            $response->assertSee($domain->status_code ?? '');
        }




    }

    public function testDomainsCheck()
    {
        $randExistingDomainCheck =  DB::table('domain_checks')->inRandomOrder()->first();
        $domainChecksCount= DB::table('domain_checks')
            ->select()
            ->where('domain_id','=',$randExistingDomainCheck->domain_id)
            ->count();

        $response = $this->post(route('domains.check', $randExistingDomainCheck->domain_id));
        $response->assertRedirect();

        $updatedDomainChecksCount= DB::table('domain_checks')
            ->select()
            ->where('domain_id','=',$randExistingDomainCheck->domain_id)
            ->count();

        assertTrue($updatedDomainChecksCount === $domainChecksCount + 1);

    }
}

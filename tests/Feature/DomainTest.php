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
        $incorrectUrl = 'asdd';
        $response = $this->post(route('domains.store'),['domain'=> ['name' => $incorrectUrl]]);
        $response->assertRedirect(route('homepage'));

        $domainData = ['name' => 'https://example@gmail.com'];

        $response = $this->post(route('domains.store'),['domain'=> $domainData]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $normalizedDomainData = ['name' => normalizeUrl($domainData['name'])];
        $this->assertDatabaseHas('domains', $normalizedDomainData);
    }

    public function testDomainsShow()
    {
       $randExistingDomain =  DB::table('domains')->inRandomOrder()->first();
       $response = $this->get(route('domains.show',$randExistingDomain->id));
       $response->assertStatus(200);

       $response->assertSee($randExistingDomain->id);
       $response->assertSee($randExistingDomain->name);
       $response->assertSee($randExistingDomain->updated_at);$response->assertSee($randExistingDomain->created_at);

       $domainsChecks = DB::table('domain_checks')
            ->where('domain_id','=',$randExistingDomain->id)
            ->orderByDesc('created_at')
            ->get();

       foreach($domainsChecks as $domainCheck) {
           $response->assertSee($domainCheck->id);
           $response->assertSee($domainCheck->status_code );
           $response->assertSee($domainCheck->h1);
           $response->assertSee($domainCheck->keywords);
           $response->assertSee($domainCheck->description);
           $response->assertSee($domainCheck->created_at);
        }
    }

    public function testDomainsIndex()
    {

//         $latestChecks = DB::table('domain_checks')
//             ->select('domain_id','status_code',DB::raw('MAX(created_at) as last_post_created_at'))
//             ->groupBy('domain_id');

//         $domainsWithLastCheck = DB::table('domains')
//             ->joinSub($latestChecks, 'latest_checks', function ($join) {
//                 $join->on('domains.id', '=', 'latest_checks.domain_id');
//             })
//             ->select('domains.id','domains.name','latest_checks.status_code','latest_checks.last_post_created_at')
//             ->get();

//         $response = $this->get(route('domains.index'));

//         foreach($domainsWithLastCheck as $domain) {
//             $response->assertSee($domain->id);
//             $response->assertSee($domain->name);
//             $response->assertSee($domain->last_post_created_at);
//             $response->assertSee($domain->status_code ?? '');
//         }

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

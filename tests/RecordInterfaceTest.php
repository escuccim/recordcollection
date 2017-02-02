<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Escuccim\RecordCollection\Models\Record;

class RecordInterfaceTest extends BrowserKitTest
{
	use DatabaseTransactions;

	public function testSearch(){
	    $this->addSampleData(30);

        // get a random record and hope it doesn't match more things than are displayed on the page
        $record = Record::inRandomOrder()->first();

        // test search  by artist
        $this->visit('/records')
            ->type($record->artist, 'searchTerm')
            ->press('Search')
            ->see($record->title)
            ->see($record->label);

        // search by title
        $this->visit('/records')
            ->type($record->title, 'searchTerm')
            ->press('Search')
            ->see($record->artist)
            ->see($record->label);

        // search by catalog number, make sure our record has a cat no
        $record = Record::whereNotNull('catalog_no')->where('catalog_no', '!=', '')->inRandomOrder()->first();

        $this->visit('/records')
            ->type($record->catalog_no, 'searchTerm')
            ->select('catalog_no', 'searchBy')
            ->press('Search')
            ->see($record->artist)
            ->see($record->title)
            ->see($record->label);

        // add records with the same label so this next test will work
        $this->addSampleData(40, 'Foobar Music');
        $labels = DB::select('select label FROM ' . config('records.table_name') . ' GROUP By label HAVING Count(label) > 23');

        if(count($labels)) {
            // pick one at random
            $count = count($labels);
            $rand = rand(0, $count - 1);
            $label = $labels[$rand];

            // get first record for this label
            $firstRecord = Record::where('label', $label->label)->orderBy('artist', 'asc')->orderBy('title', 'asc')->first();
            $lastRecord = Record::where('label', $label->label)->orderBy('artist', 'desc')->orderBy('title', 'desc')->first();

            $this->visit('/records/search?searchTerm=' . $label->label . '&searchBy=label')
                ->see($firstRecord->artist)
                ->see($firstRecord->title)
                ->dontSee($lastRecord->title);

            // change the sort
            $firstRecord = Record::where('label', $label->label)->orderBy('catalog_no', 'asc')->orderBy('artist', 'asc')->orderBy('title', 'asc')->first();
            $lastRecord = Record::where('label', $label->label)->orderBy('catalog_no', 'desc')->orderBy('artist', 'desc')->orderBy('title', 'desc')->first();

            $this->visit('/records/search?searchTerm=' . $label->label . '&searchBy=label&sort=catalog_no')
                ->see($firstRecord->artist)
                ->see($firstRecord->title)
                ->see($firstRecord->catalog_no)
                ->dontSee($lastRecord->title);
        }
    }

    public function testPagination(){
        // put some data in the DB so we have something to test
        $data = $this->addSampleData(60);

        // test pagination - get number of pages
        $resultsPerPage = 23;
        $count = Record::count();
        $numPages = ceil($count / $resultsPerPage);
        // pick a random page and figure out how many records to skip
        $page = rand(1, $numPages);
        $numToSkip = ($resultsPerPage * ($page - 1)) + 2;

        // see what data will appear on that page
        $record = Record::orderBy('label', 'asc')->orderBy('artist', 'asc')->orderBy('title', 'asc')->skip($numToSkip)->first();

        $this->visit('/records?page=' . $page)
            ->see($record->artist)
            ->see($record->title);

        $this->addSampleData(50);

        // test sort by artist
        $record = Record::orderBy('artist', 'asc')->orderBy('label', 'asc')->orderBy('artist', 'asc')->orderBy('title', 'asc')->skip($numToSkip)->first();

        $this->visit('/records?sort=artist&page=' . $page)
            ->see($record->artist)
            ->see($record->title)
            ->see($record->label);

        // test sort by catalog no
        $record = Record::orderBy('catalog_no', 'asc')->orderBy('label', 'asc')->orderBy('artist', 'asc')->orderBy('title', 'asc')->skip($numToSkip)->first();

        $this->visit('/records?sort=catalog_no&page=' . $page)
            ->see($record->artist)
            ->see($record->title)
            ->see($record->label);
    }

    public function testAPI(){
        // put some data in the DB so we have something to test
        $data = $this->addSampleData(20);

        $admin = $this->createTestUser(0);
        $token = $admin->api_token;

        // make sure it doesn't work without a token
        $this->visit('/api/records')
            ->dontSee('results');

        // try it now with a token
        $this->visit('/api/records?api_token=' . $token)
            ->assertResponseOk()
            ->see('results');

        // do a search that should have no results, assert response is 404
        $response = $this->call('GET', '/api/records?api_token=' . $token . '&artist=borkborkborkbar');
        $this->assertEquals(404, $response->status());


        // do a search that should have results by label
        $label = Record::select('label')->distinct()->inRandomOrder()->first();
        $records = Record::where('label', $label->label)->get();

        // check to see that the items listed on the page match the results in the DB
        $this->visit('/api/records?api_token=' . $token . '&searchTerm=' . $label->label)
            ->assertResponseOk()
            ->see($label->label)
            ->see(count($records));

        // check that the records that should be there are there
        foreach($records as $record){
            $this->visit('/api/records?api_token=' . $token . '&searchTerm=' . $label->label)
                ->see(json_encode($record->artist))
                ->see(json_encode($record->title));
        }

        // do a search that should have results by artist
        $artist = Record::select('artist')->where('artist', 'NOT LIKE', '%&%')->distinct()->inRandomOrder()->first();
        $records = Record::where('artist', $artist->artist)->get();

        // check to see that the items listed on the page match the results in the DB
        $this->visit('/api/records?api_token=' . $token . '&searchTerm=' . $artist->artist)
            ->assertResponseOk()
            ->see(json_encode($artist->artist))
            ->see(count($records));

        // check that the records that should be there are there
        foreach($records as $record){
            $this->visit('/api/records?api_token=' . $token . '&searchTerm=' . $artist->artist)
                ->see(json_encode($record->artist))
                ->see(json_encode($record->title));
        }

        // do a search that should have results by title
        $title = Record::select('title')->distinct()->inRandomOrder()->first();
        $records = Record::where('title', $title->title)->get();

        // check to see that the items listed on the page match the results in the DB
        $this->visit('/api/records?api_token=' . $token . '&searchTerm=' . $title->title)
            ->assertResponseOk()
            ->see(json_encode($title->title))
            ->see(count($records));

       // check that the results that should be there re
        foreach($records as $record){
            $this->visit('/api/records?api_token=' . $token . '&searchTerm=' . $title->title)
                ->see(json_encode($record->artist))
                ->see(json_encode($record->title));
        }

    }

    private function createTestUser($admin = 0){
        $user = factory(App\User::class)->create();
        $user->type = $admin;
        $user->api_token = 'TEST_TOKEN_123';
        $user->save();

        return $user;
    }

    private function generateTestData(){
        $faker = Faker\Factory::create();
        $data = [
            'artist'    => $artist = $faker->name,
            'title'     => $faker->streetName,
            'label'     => $faker->company,
            'catalog_no'    => $faker->isbn10,
            ];

        return $data;
    }

    private function addSampleData($count = 5, $label = NULL){
        for($i = 1; $i <= $count; $i++) {
            $data = $this->generateTestData();

            $record = new Record();
            $record->artist = $data['artist'];
            $record->title = $data['title'];
            if(!$label)
                $record->label = $data['label'];
            else
                $record->label = $label;
            $record->catalog_no = $data['catalog_no'];
            $record->save();

            $returnArray[] = $record;
        }

        return $returnArray;
    }
}

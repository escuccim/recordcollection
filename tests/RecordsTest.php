<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Escuccim\RecordCollection\Models\Record;

class RecordsTest extends TestCase
{
	use DatabaseTransactions;
	/**
     * A basic test example.
     *
     * @return void
     */
	
    public function testSearch(){
    	// get a random record and hope it doesn't match more things than are displayed on the page
    	$record = Record::inRandomOrder()->first();
    	
    	// test search  by artist and title
    	$this->visit('/records')
    		->type($record->artist, 'searchTerm')
    		->press('Search')
    		->see($record->title)
    		->see($record->label);
    	
    	$this->visit('/records')
    		->type($record->title, 'searchTerm')
    		->press('Search')
    		->see($record->artist)
    		->see($record->label);
    	
    	// test search by catalog number
    	$record = Record::whereNotNull('catalog_no')->where('catalog_no', '!=', '')->inRandomOrder()->first();
    	
    	$this->visit('/records')
    		->type($record->catalog_no, 'searchTerm')
    		->select('catalog_no', 'searchBy')
    		->press('Search')
    		->see($record->artist)
    		->see($record->title)
    		->see($record->label);
    		
    	// Test search, plus reorder of results - don't feel like writing code to get a label that has 
    	// more records than are displayed on the page, so I'm leaving this hard coded for now
    	
    	// get labels that have more results than will fit on one page
    	$labels = DB::select('select label FROM ' . config('records.table_name') . ' GROUP By label HAVING Count(label) > 23');
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
    
    
    public function testRecordPaginationAndSort(){
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
    
    public function testPermissionsWithoutUser(){
    	// without a user
    	$this->visit('/records')
    		->see('Search')
    		->see('Search By')
    		->see('Artist')
    		->dontSee('Add Record');

    	// get a record
        $record = Record::inRandomOrder()->first();

        // check it's page
        $this->visit('/records/' . $record->id)
            ->see($record->artist)
            ->see($record->title)
            ->dontSee('Edit');
    } 
    
    public function testPermissionsWithNonAdminUser(){
    	// with a user
    	$user = factory(App\User::class)->create();
    	
    	// make sure admin links don't show up for non-admin
    	$this->actingAs($user)
    		->visit('/records')
	    	->see('Search')
	    	->see('Search By')
	    	->see('Artist')
	    	->dontSee('Add Record');

        // get a record
        $record = Record::inRandomOrder()->first();

    	$this->actingAs($user)
    		->visit('/records/' . $record->id)
	    	->see('Record Info')
	    	->see($record->artist)
	    	->see($record->title)
	    	->dontSee('Edit Record');
    	
	    // make sure that user can't access pages they shouldn't be able to
	    $response = $this->call('GET', '/records/'. $record->id . '/edit');
	    $this->assertEquals(404, $response->status());
	    	
	    // make sure that user can't access pages they shouldn't be able to
	    $response = $this->call('GET', '/records/create');
	    $this->assertEquals(404, $response->status());
	    
	    // destroy user
	    $user->destroy($user->id);
    }
    
    public function testPermissionsWithAdminUser(){
    	// with admin user
    	$user = factory(App\User::class)->create();
    	$user->type = 1;
    	$user->save();
    	 
    	// see that all proper links appear
    	$this->actingAs($user)
	    	->visit('/records')
	    	->see('Search')
	    	->see('Search By')
	    	->see('Artist')
	    	->see('Add Record')
    		->click('Add Record')
    		->assertResponseOk()
    		->see('New Record')
    		->see('Add Record');
	    
    	// pick a random record
    	$record = Record::inRandomOrder()->first();
    		
    	$this->actingAs($user)
	    	->visit('/records/' . $record->id)
	    	->see('Record Info')
	    	->see($record->artist)
	    	->see($record->title)
	    	->see($record->lable)
	    	->see('Edit Record')
    		->click('Edit Record')
    		->see('Edit Record')
    		->see('Update Record')
    		->press('Update Record')
    		->see('Your record has been updated!')
    		->seePageIs('/records/' . $record->id);
    	
    	// destroy user
    	$user->destroy($user->id);
    }
    
    public function testEditRecord(){
    	$admin = factory(App\User::class)->create();
    	$admin->type = 1;
    	
    	// pick a random record
    	$record = Record::inRandomOrder()->first();
	
    	$this->actingAs($admin)
    		->visit('/records/' . $record->id . '/edit')
    		->assertResponseOk()
    		->type('Test Artist', 'artist')
    		->press('Update Record')
    		->see('Your record has been updated!')
    		->see('Test Artist');
    	
    	// destroy user
    	$admin->destroy($admin->id);
    }
    
    public function testAddRecord(){
    	$admin = factory(App\User::class)->create();
    	$admin->type = 1;

    	$label = Record::inRandomOrder()->first()->label;

    	$this->actingAs($admin)
    		->visit('/records/create')
    		->type('Test Artist', 'artist')
    		->type('Test Title', 'title')
    		->select($label, 'label')
    		->type('XXX-1234', 'catalog_no')
    		->press('Save')
    		->see('The record has been created');
    	
    	$this->seeInDatabase('records_new', [
    			'title' => 'Test Title'
    	]);
    	
    	$admin->destroy($admin->id);
    }
    
    public function testAPI(){
    	$token = 'SKOOCH_API';
    	
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
    	
   		
   		/** do a search that should have results by label **/
   		$label = Record::select('label')->distinct()->inRandomOrder()->first();
   		$records = Record::where('label', $label->label)->get();
   		
		// check to see that the items listed on the page match the results in the DB
   		$this->visit('/api/records?api_token=' . $token . '&searchTerm=' . $label->label)
   			->assertResponseOk()
   			->see($label->label)
   			->see(count($records));
   	
   		// do the search for label
   		$this->visit('/api/records?api_token=' . $token . '&label=' . $label->label)
   			->assertResponseOk()
   			->see($label->label)
   			->see(count($records));
   		
   			
   		/** do a search that should have results by artist **/
   		$artist = Record::select('artist')->distinct()->inRandomOrder()->first();
   		$records = Record::where('artist', $artist->artist)->get();
   			 
   		// check to see that the items listed on the page match the results in the DB
   		$this->visit('/api/records?api_token=' . $token . '&searchTerm=' . $artist->artist)
   			->assertResponseOk()
   			->see(json_encode($artist->artist))
   			->see(count($records));
   			
   		// do the search for artist
   		$this->visit('/api/records?api_token=' . $token . '&artist=' . $artist->artist)
   			->assertResponseOk()
   			->see(json_encode($artist->artist))
   			->see(count($records));
    	
   			
   		/** do a search that should have results by title **/
   		$title = Record::select('title')->distinct()->inRandomOrder()->first();
   		$records = Record::where('title', $title->title)->get();
   				
   		// check to see that the items listed on the page match the results in the DB
   		$this->visit('/api/records?api_token=' . $token . '&searchTerm=' . $title->title)
   			->assertResponseOk()
   			->see(json_encode($title->title))
   			->see(count($records));
   			
   		// do the search for title
   		$this->visit('/api/records?api_token=' . $token . '&title=' . $title->title)
   			->assertResponseOk()
   			->see(json_encode($title->title))
   			->see(count($records));
   			
    }
}

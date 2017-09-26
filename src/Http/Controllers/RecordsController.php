<?php

namespace Escuccim\RecordCollection\Http\Controllers;

use Illuminate\Http\Request;
use Escuccim\RecordCollection\Models\Record;
use Escuccim\RecordCollection\Models\RecordSearch;
use Escuccim\RecordCollection\Http\Requests\RecordRequest;
use GuzzleHttp\Client as GuzzleHttpClient;
use App\Http\Controllers\Controller;

class RecordsController extends Controller
{
	/**
	 * Sets middleware permissions to only allow access to public viewable pages. Other pages require admin access.
	 */
	public function __construct() {
		$this->middleware(config('records.admin_middleware'))->except(['search', 'index', 'show', 'api']);
	}
	
	/** 
	 * this is called by ajax to return the results of a record search, if no search specified it returns all records 
	 * Input: request containing search, either as post or get
	 * Return: view containing results, to be loaded into div with Ajax
	 **/
	public function search(Request $request){
		$sort = $request->input('sort', 'label');
		$recordSearch = new RecordSearch($request, $sort);
		
		$records = $recordSearch->buildQuery();
		$records->setPath('/records');

		return view('records::records.recordList', compact('records', 'recordSearch', 'sort'));
	}
	
 	/** 
 	 * this loads the record list page, specifying default values for sort and page 
 	 * Input: request (used to contain search until that was separated out
 	 * Return: view containing results
 	 **/
    public function index(Request $request) {
    	$sort = $request->input('sort', 'label');
    	$page = $request->input('page', 0);
    	$recordSearch = new RecordSearch($request, $sort);
    	
    	$records = $recordSearch->buildQuery();

		return view('records::records.index', compact('records', 'sort', 'recordSearch'));
	}
	
	/**
	 *  Show the detail for a specific record
	 *  Input: id of record to show
	 *  Return: view with that record's info
	 **/
	public function show($id, $slug = null) {
		$record = Record::find($id);
		if(!$record){
            return view('records::records.error');
        }
		$title = trans('record-lang::records.info') . ' - ' . $record->title;
		return view('records::records.show', compact('record', 'title'));
	}
	
	/** 
	 * shows edit form for a specific record, specified by id 
	 **/
	public function edit($id) {
		$record = Record::findOrFail($id);
		
		$labels = Record::listLabels()->toArray();
        $title = 'Edit Record';

		return view('records::records.edit', compact('record', 'labels', 'title'));
	}
	
	/** 
	 * updates a record, specified by ID 
	 **/
	public function update($id, RecordRequest $request){
		// get the record
		$record = Record::findOrFail($id);
	
		$record->label = $request->label;
		$record->Artist = $request->artist;
		$record->Title = $request->title;
		$record->Catalog_No = $request->catalog_no;
		$record->Style = $request->style;
		$record->discogs = $request->discogs;
		$record->thumb = $request->thumb;
		
		$record->update();
		flash()->success('Your record has been updated!');
		
		return redirect('records/' . $id);
	}
	
	/** 
	 * show blank add record form 
	 **/
	public function create(){
		$record = new Record;	
		$labels = Record::listLabels()->toArray();
        $title = 'New Record';
		return view('records::records.create', compact('record', 'labels', 'title'));
	}
	
	/**
	 * Saves a new record in the database.
	 * 
	 * @param RecordRequest $request
	 * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
	 */
	public function store(RecordRequest $request){
		$record = new Record;
	
		$record->label = $request->label;
		$record->artist = $request->artist;
		$record->title = $request->title;
		$record->catalog_no = $request->catalog_no;
		$record->discogs = $request->discogs;
		$record->thumb = $request->thumb;
		
		$record->save();
		
		flash()->success('The record has been created');
		
		return redirect('records');
	}
	
	public function destroy($id) {
		Record::destroy($id);
		return redirect('records');
	}
	
	/**
	 * Takes in the search and sort parameters from the Request, builds and executes a RecordSearch and returns
	 * the results as JSON. If no results found returns message with 404 status.
	 * 
	 * @param Request $request
	 * @return results
	 */
	public function api(Request $request){
		$sort = $request->input('sort', 'label');
		
		$recordSearch = new RecordSearch($request, $sort);
		
		$records = $recordSearch->buildDetailQuery();
		
		if(count($records)){	
			$results = [
					'items' => count($records),
					'results' => $records,
			];
			return response()->json($results);
		} else { 
			$results = ['message' => 'No matching records found'];
			return response()->json($results, 404);
		}
		
	}
	
	/***
	 * Make API request to discogs to try to match their data to my DB
	 * I had to babysit this through, changing a few parameters at a time to maximize the matches
	 */
	public function discogs(){
		$client = new GuzzleHttpClient([
			'base_uri' => 'https://api.discogs.com/database/search',
			'headers' => ['User-Agent' => 'skoo.ch/0.1'],
		]);
		
		$records = Record::whereNull('discogs')
					->whereNotNull('catalog_no')
					->skip(0)->take(60)
					->get();

		foreach($records as $record){
			
//			echo "<p> " . $record->id . " Artist: " . $record->artist . " || Title: " . $record->title . " || Label : " . $record->label . "<br>";
			
			$response = $client->request('GET', 'https://api.discogs.com/database/search',
					['query' => [
							'token' => 'YPlqDghLtBoJTzGWvcZODIGknRpCHMlzEmTvXliD',
							'format' => 'vinyl',
							'catno' => $record->catalog_no,
							'artist' => $record->artist,
							'title' => $record->title,
							'label' => $record->label,
							'type' => 'release',
					]]);
			
			$content = json_decode($response->getBody()->getContents());
			$results = $content->results;
			
			if(count($results) > 0){
				// if there's only one result use it
				if(count($results) == 1){
					$record->discogs = $results[0]->uri;
					$record->thumb = $results[0]->thumb;
					$record->discogs_results = NULL;
					if(!$record->catalog_no)
						$record->catalog_no = $results[0]->catno;
					$record->save();
					$this->dumpRecord($record);
				} else {
					// else remove the promos and white labels and such
					$count = count($results);
					for($i = 0; $i < $count; $i++){
						// if the format array contains one of these, let's remove the record
						if(in_array('Unofficial Release', $results[$i]->format) || in_array('Reissue', $results[$i]->format) || in_array('Repress', $results[$i]->format) || in_array('Mispress', $results[$i]->format) || in_array('Misprint', $results[$i]->format) || in_array('White Label', $results[$i]->format) || in_array('Promo', $results[$i]->format) || in_array('Test Pressing', $results[$i]->format) || in_array('Limited Edition', $results[$i]->format)){
							unset($results[$i]);
						}
					}

					// if there's only one result left one use it
					if(count($results) == 1){
						foreach($results as $result){
							$record->discogs = $result->uri;
							$record->thumb = $result->thumb;
							$record->catalog_no = $result->catno;
							$record->discogs_results = NULL;
							$record->save();
							$this->dumpRecord($record);
						}
					} else {
						$matchFound = 0;
						// if one of the entries only has one label in it, and it matches the one in my DB, use it
						foreach($results as $result){
							if(count($result->label) == 1){
								if($result->label[0] == $record->label){
									$record->discogs = $result->uri;
									$record->thumb = $result->thumb;
									$record->catalog_no = $result->catno;
									$record->discogs_results = NULL;
									$record->save();
									$this->dumpRecord($record);
									$matchFound++;
									break;
								}
							}
						}
						
						if(!$matchFound){
							// set the number of results returned
							$record->discogs_results = count($results);
							$record->save();
						}
					}
				}
			}
		}
	}
	
	/**
	 * Populate catalog numbers for records we already matched to discogs
	 */
	public function populateCatalogNumbers(){
		$records = Record::whereNotNull('discogs')
					->get();
		
		$client = new GuzzleHttpClient([
				'base_uri' => 'https://api.discogs.com/releases/',
				'headers' => ['User-Agent' => 'skoo.ch/0.1'],
		]);
					
		foreach($records as $record){
			// get the release number from the discogs data
			$discogsData = explode('/', $record->discogs);
			$count = count($discogsData) - 1;
			$releaseNumber = $discogsData[$count];
			
			echo "<p>" . $record->discogs . "<br>  ";
	
			$response = $client->request('GET', 'https://api.discogs.com/releases/' . $releaseNumber,
					['query' => [
							'token' => 'YPlqDghLtBoJTzGWvcZODIGknRpCHMlzEmTvXliD',
					]]);
				
			$content = json_decode($response->getBody()->getContents());
			
			$labels = $content->labels;

 			foreach($labels as $label){
				if($label->catno){
					$record->catalog_no = $label->catno;
					echo $label->catno . "<br>";
				}
			}
 			$record->save();			
		}
	}
	
	/**
	 * For a given record outputs the details to the screen for debugging purposes.
	 * 
	 * @param Record $record
	 * @return boolean
	 */
 	private function dumpRecord(Record $record){
 		echo $record->artist . "<br>";
 		echo $record->title . "<br>";
 		echo $record->label . "<br>";
 		echo $record->catalog_no . "<br>";
 		echo "<p>";
 		
 		return true;
 	}
}


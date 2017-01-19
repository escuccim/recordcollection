<?php
namespace Escuccim\RecordCollection\Models;

use DB;

class RecordSearch {
	public $formData;

	/**
	 * Sets the variables based on the Request passed in. The request contains the search fields, the sort is
	 * passed in separately. Note that this sets the parameters for a detailed search by individual fields although
	 * the HTML interface only has searchTerm and searchBy. The other parameters are used for API requests.
	 * 
	 * @param Request $request
	 * @param string $sort
	 */
	public function __construct($request = null, $sort = null) {
		if(isset($request)){
			$this->formData['artist'] = $request->input('artist');
			$this->formData['title'] = $request->input('title');
			$this->formData['label'] = $request->input('label');
			$this->formData['catalog_no'] = $request->input('catalog_no');
			$this->formData['searchTerm'] = $request->input('searchTerm');
			$this->formData['searchBy'] = $request->input('searchBy');
			if($sort) {
				if($sort == 'labels.name'){
					$sort = 'label';
				}
				$this->formData['sortBy'] = $sort;
			} else 
				$this->formData['sortBy'] = 'label';
			
		}
	}
	/**
	 * Build the query to get the records from the DB based on the search and sort parameters.
	 * Paginate is a boolean that indicates whether the results are for HTML display or not. If not they are
	 * for API data so the query will contain different fields and will not be paginated.
	 * 
	 * @param boolean $paginate
	 * @return query results
	 */
	public function buildQuery($paginate = TRUE){
			
		$likeString = "%" . trim($this->formData['searchTerm']) . "%";

		$compareString = $this->formData['searchBy'];
		$compareOperator = 'LIKE';

		if($paginate)
			$selectFields = ['records_new.*'];
		else 
			$selectFields = [
					'records_new.artist',
					'records_new.title',
					'records_new.label',
					'records_new.catalog_no',
					'records_new.discogs',
					'records_new.thumb',
					'records_new.created_at',
					'records_new.updated_at',
			];
			
		if($compareString == '')
			$compareString = 'all';
		
		if($compareString != 'all'){
			// this is not even used anymore, but kept here if I decide to use it again
		    if($compareString == 'strict.label') {
				$compareString = 'label';
				$compareOperator = '=';
				$likeString = trim($this->formData['searchTerm']);
			}
				
			$query = Record::select($selectFields)
						->where($compareString, $compareOperator, $likeString)
						->orderBy($this->formData['sortBy'], 'asc')
						->orderBy('label', 'asc')->orderBy('artist', 'asc')->orderBy('title', 'asc')
						;
		} else {
			$query = Record::select($selectFields)
							->where('artist', 'LIKE', $likeString)
							->orWhere('title', 'LIKE', $likeString)
							->orWhere('label', 'LIKE', $likeString)
							->orWhere('catalog_no', 'LIKE', $likeString)
							->orderBy($this->formData['sortBy'], 'asc')
							->orderBy('label', 'asc')->orderBy('artist', 'asc')->orderBy('title', 'asc')
							;
		}
		if($paginate)
			$query = $query->paginate(config('records.results_per_page'));
		else 
			$query = $query->get();
			
		return $query;
	}

    /**
     * I don't know if this is even used anywhere. Can possibly be removed.
     * @return mixed
     */
	public function buildDetailQuery(){
		$selectFields = [
				'records_new.artist',
				'records_new.title',
				'records_new.label',
				'records_new.catalog_no',
				'records_new.discogs',
				'records_new.thumb',
				'records_new.created_at',
				'records_new.updated_at',
		];
		
		$query = Record::select($selectFields)
						->orderBy($this->formData['sortBy'], 'asc')
						->orderBy('label', 'asc')->orderBy('artist', 'asc')->orderBy('title', 'asc');
		
		if(trim($this->formData['artist']) == true) {
			$query = $query->where('artist', 'LIKE', '%' . $this->formData['artist'] . '%');
		}
		if(trim($this->formData['title']) == true) {
			$query = $query->where('title', 'LIKE', '%' . $this->formData['title'] . '%');
		}
		if(trim($this->formData['label']) == true) {
			$query = $query->where('label', 'LIKE', '%' . $this->formData['label'] . '%');
		}
		if(trim($this->formData['catalog_no']) == true) {
			$query = $query->where('catalog_no', 'LIKE', '%' . $this->formData['catalog_no'] . '%');
		}

		$query = $query->get();
		return $query;
	}

}
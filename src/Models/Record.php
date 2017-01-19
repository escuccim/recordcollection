<?php

namespace Escuccim\RecordCollection\Models;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
	protected $table = 'records_new';
	
	protected $fillable = [
		'artist',
		'title',
		'label',
		'catalog_no',
		'label_id',
		'discogs',
		'thumb',
		'style',
	];

	public function __construct(){
	    $this->table = config('records.table_name');
    }

	/**
	 * Get a list of distinct labels from the records table. I don't know if it's really necessary to have the labels
	 * 
	 * @return collection of labels
	 */
	public static function listLabels(){
		$labels = Record::select('label')->distinct()->orderBy('label', 'asc')->get()->pluck('label', 'label');
		
		return $labels;
	}
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Staffs\StaffsRepository;
use Elasticsearch\Client;
use Illuminate\Support\Arr;

class StaffController extends Controller
{
	private $elasticsearch;

    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    public function search(){
        $query = [
                    'match_all' => (object)[],
                ];

        $items = $this->elasticsearch->search([
            'index' => 'staff_latest',
            'body' => [
                'query' => $query
            ]
        ]);

        return $items;
    }

    private function buildCollection(array $items): Collection
    {
        $ids = Arr::pluck($items['hits']['hits'], '_id');
        return Stff::whereIn('id',$ids)->get()
            ->sortBy(function ($article) use ($ids) {
                return array_search($article->getKey(), $ids);
            });
    }

	//   public function search(StaffsRepository $repository){
	//   	if(count(request()->all()) > 0){
		// 	$par = implode(" ",request()->all());
		// }else{
		// 	$par = request('q');
		// }
	 //    $staffs = $repository->search( (String) $par );
	 //    if(request()->has('year')){
	 //    	$custom = collect();
	 //    	$custom->put('Male', $staffs->where('gender',1)->count());
	 //    	$custom->put('Female', $staffs->where('gender',2)->count());
	 //    	$custom->put('Regular', $staffs->where('employement_type',1)->count());
	 //    	$custom->put('Irregular', $staffs->where('employement_type',2)->count());
	 //    	return response()->success(false,'Success',$custom); 
	 //    }else{
	 //    	return response()->success(false,'Success',$staffs); 
	 //    }
  	//   }
}

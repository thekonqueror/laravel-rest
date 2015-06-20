<?php

class StatesController extends \BaseController {
	
	//Get list of cities in a state
	public function cities($state)
	{
		$cities = Cities::where('state', strtoupper($state))->Paginate(50)->toArray();

		// If no results were found, ask for a valid state code. Return code 406 - Input not acceptable
		if(empty($cities['total']))
			return Response::make('Please enter a valid state code.', 406);
			
		return Response::json($cities,
			200
		);
	}

	// Find nearby cities
	public function citiesinradius($state, $city)
	{
		$basecity = Cities::find($city);

		// If no results were found for a city, ask for a valid city ID. Return code 406 - Input not acceptable
		if($basecity == NULL)
			return Response::make('Please enter a valid city ID.', 406);
			
		$slatitude = $basecity->latitude;
		$slongitude = $basecity->longitude;
		
		$radius = Input::get('radius');
		
		// If radius is not given, assume 100 Miles
		if($radius == NULL)
			$radius = '100';
		
		// Get a list of cities sorted by distance
		$cities = DB::table('cities')->select(DB::raw("id,name,state, ( 3959 * acos( cos( radians('$slatitude') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('$slongitude') ) + sin( radians('$slatitude') ) * sin(radians(latitude)) ) ) AS distance "))->having('distance','<',$radius)->orderBy('distance')->get();
		
		// Manual pagination for results
		$pagination = Paginator::make($cities, count($cities), 50)->toArray();
		$perPage = 50;
		$page = Input::get('page', 1);

		if ($page > $pagination['total'] || $page > $pagination['last_page']) 
		{
			$page = 1;
		}

		$offset = ($page * $perPage) - $perPage;
		$articles = array_slice($pagination['data'], $offset, $perPage);
		$data = Paginator::make($articles, count($pagination['data']), $perPage);
		
		return Response::json($data->toArray(),
			200
		);
	}

}

<?php
// Exception handler to return error code 406 if a valid city and state was not found.
use Illuminate\Database\Eloquent\ModelNotFoundException;

App::error(function(ModelNotFoundException $e)
{
    return Response::make('Please enter a valid city and state name.', 406);
});

class UsersController extends \BaseController {

	// List cities a user has visited
	public function visits($id)
	{
		$visits = DB::table('visits')->join('cities','visits.cityid','=','cities.id')->where('visits.userid',$id)->select('visits.id', 'cities.name','cities.state')->get();

		return Response::json($visits,200);
	}
	
	// Store visit data to DB and return a visit ID
	public function storevisits($id)
	{

		$data = Input::all();
		$city = $data['city'];
		$state = $data['state'];
		
		$citydata = Cities::where('name',$city)->where('state', strtoupper($state))->firstOrFail();
		$visit = Visits::where('userid',$id)->where('cityid',$citydata->id)->first();
		
		// Update if the visit exists else create a new entry
		if($visit == NULL)
			$visit = new Visits;
		
		$visit->userid = $id;
		$visit->cityid = $citydata->id;
		$visit->save();
		
		return Response::json(array(
			'Visit ID' => $visit->id),
			200
		);
	}
	
}

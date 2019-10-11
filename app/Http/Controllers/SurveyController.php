<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Survey;
use App\SurveyPublisher;
use App\Publisher;
use App\SurveyTracking;
use Ramsey\Uuid\Uuid;
use Cookie;
use DB;
use Illuminate\Support\Facades\Redirect;

class SurveyController extends Controller
{
	public function getSurvey(Request $request,$id)
	{
		$survey = Survey::with('client')->find($id);
		//with('survey')->with('publisher')->
		$publishers = SurveyPublisher::where('survey_id',$id)->get();
		
		return response()->json(['survey' => compact('survey'), 'publishers' => $publishers] );
	}

	public function updateSurvey(Request $request)
	{
		$id = $request->input('id');
		
		$survey = Survey::find($id);
		$rules = [
			'name'  => 'required',
			'client'    => 'required',
			'link'    => 'required'
		];

		$this->validate($request, $rules);
		
		$survey->survey_name = $request->input('name');
		$survey->client_id = $request->input('client');
		$survey->link = $request->input('link');
		$survey->status = 1;//$request->input('status');
		$survey->save();

				//SurveyPublisher::where('survey_id',$id)->delete();


		$publishers = $request->input('publishers');
        foreach ($publishers as $publisher) {
        	echo'--22--'.$publisher['name'];
        	echo'--222222--'.$id;
        	$surveyPub = SurveyPublisher::where('survey_id',$id)->where('publisher_id',$publisher['name'])->first();
        	
	       	if(!empty($surveyPub)){
	        	$surveyPub->survey_id = $id;
				$surveyPub->publisher_id = $publisher['name'];
				$surveyPub->link1 = $publisher['link1'];
				$surveyPub->link2 = $publisher['link2'];
				$surveyPub->link3 = $publisher['link3'];
				$surveyPub->status = 1;		
				$surveyPub->save();
			}else{			
	        	$sp = SurveyPublisher::create([
	        		'survey_id'     => $id,
			        'publisher_id'  => $publisher['name'],
			        'link1'         => $publisher['link1'],
			        'link2'         => $publisher['link2'],
			        'link3'         => $publisher['link3'],
			        'status'        => 1,
	        	]);
	        	$sp->save();
        	}
        }
		return response()->json(compact('survey'));
	}

	public function addSurvey(Request $request)
	{
		$rules = [
			'name'  => 'required',
			'client'    => 'required',
			'link'    => 'required'
		];

		$this->validate($request, $rules);


		$uuid1 = Uuid::uuid1();
		$uuid2 = Uuid::uuid1();
		$survey = Survey::create([
            'survey_name'     => $request->input('name'),
            'survey_uuid'     => $uuid1,
            'survey_cookie'   => $uuid2,
            'client_id'       => $request->input('client'),
            'link'       => $request->input('link'),
            'status'          => 1,
        ]);

        $survey->save();

        $publishers = $request->input('publishers');
        foreach ($publishers as $publisher) {
        	$sp = SurveyPublisher::create([
        		'survey_id'     => $survey->id,
		        'publisher_id'  => $publisher['name'],
		        'link1'         => $publisher['link1'],
		        'link2'         => $publisher['link2'],
		        'link3'         => $publisher['link3'],
		        'status'        => 1,
        	]);
        	$sp->save();
        }
	
		return response()->json(compact('survey'));
	}

	public function getAllSurvey(Request $request)
	{
		$survey = Survey::with('client')->orderBy('surveys.id','DESC')->get();
		foreach ($survey as $key => $s) {
			$s->publishers = SurveyPublisher::with('publisher')->where('survey_id',$s->id)->orderBy('survey_publishers.id','DESC')->get();
		}
		return response()->json(compact('survey'));
	}

	public function getSurveyPublisher(Request $request,$id) {
		$survey = Survey::with('client')->find($id);
		$publishers = SurveyPublisher::with('publisher')->where('survey_id',$id)->get();
		
		return response()->json(['survey' => compact('survey'), 'publishers' => $publishers] );
	}
	public function getSurveyReport(Request $request) {
		
		$sid = $request->input('survey');
		$pid = $request->input('publisher');
		
		$items = DB::table("survey_trackings")
            ->select(DB::raw('DATE(landing_time) as action_date,count(*) as clicks,SUM(CASE 
					             WHEN final_status=1 THEN 1
					             ELSE 0
					           END) AS ss,SUM(CASE 
					             WHEN final_status=2 THEN 1
					             ELSE 0
					           END) AS tm,SUM(CASE 
					             WHEN final_status=3 THEN 1
					             ELSE 0
					           END) AS qf'))
            ->where('survey_id',$sid);
            if(!empty($pid)) {
	            $items = $items->where('publisher_id',$pid);
	        }
            $items = $items->groupBy(DB::raw("DATE(landing_time)"))
            ->get();

        return response()->json(compact('items'));	
	}

	public function getSurveyDetailReport(Request $request) {
		$sid = $request->input('sid');
		$pid = $request->input('pid');
		$action_date = $request->input('action_date');
		$type = $request->input('type');
		$items = DB::table("survey_trackings")
            ->select(DB::raw('user_ip as ip,user_id,user_agent, user_referer, landing_time as action_time, final_update_time as final_time, TIMEDIFF(final_update_time, landing_time) AS datediff'))
            ->where('survey_id',$sid);
            if(!empty($pid) && $pid != 0) {
	            $items = $items->where('publisher_id',$pid);
	        }
	        if(!empty($action_date) ) {
	            $items = $items->where(DB::raw('DATE(landing_time)'),$action_date);
	        }
	        if(!empty($type)) {
	        	$status = 1;
				if(strtolower($type) == 'success') {
					$status = 1;
				}else if(strtolower($type) == 'quotafull') {
					$status = 2;
				}else if($type == 'terminate') {
					$status = 3;
				}
	            $items = $items->where('final_status',$status);
	        }
            $items = $items->get();

        return response()->json(compact('items'));		
	}


	public function surveyStart(Request $request,$id) {
		// survey intermediate - means we land on a survey page
		// Here we will set cookies for a survey
		$uid = $id;
		$survey = Survey::where('survey_uuid',$uid)->first();
		if(!empty($survey)) {

			$sid = $uid;
			$pid = 0;
			if($request->input('pid') !== null) {
				$pid = $request->input('pid');
			}

			$supcd = 0;
			if($request->input('supcd') !== null) {
				$supcd = $request->input('supcd');
			}
			//Genrate new user id;
			$userid = rand(4000000,7000000);
			$client_link = $survey->link;

			$cookie_name = $survey->survey_cookie;
			// save SID
			Cookie::queue($cookie_name.'-sid', $sid, 60);
			// save new UID
			Cookie::queue($cookie_name.'-uid', $userid, 60);
			
			if(! empty($pid)) {
				Cookie::queue($cookie_name.'-pid', $pid, 60);
				$publishers = Publisher::where('p_uuid',$pid)->first();
				if($publishers) {					
					$supcd = $publishers->supcode;					
					if($supcd!= '') {						
						if($request->input($supcd) !== null) {
							$supcode = $request->input($supcd);
							Cookie::queue($cookie_name.'-'.$supcd, $supcode, 60);
						}						
					}
				}	
			}			
			

			$useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
			$userip = $_SERVER['REMOTE_ADDR'];
			$userreferer = '';
			if(!empty($_SERVER['HTTP_REFERER'])) {
				$userreferer = $_SERVER['HTTP_REFERER'];
			}
			$current_time = date('Y-m-d H:i:s');

			$st = SurveyTracking::create([
	            'survey_id'          => $sid,
	            'user_id'            => $userid,
	            'publisher_id'       => $pid,
	            'user_agent'         => $useragent,
	            'user_ip'            => $userip,
	            'user_referer'       => $userreferer,
	            'landing_time'       => $current_time,
	            'final_update_time'  => $current_time
	        ]);

	        $st->save();

			return Redirect::to($client_link);
		}
		
		// we will redirect on client page
		return Redirect::to('survey/track');
	}

	public function surveyTrack(Request $request,$id) {
		$uid = $id;
		$data = explode('-',$uid);
		$sid = [];
		foreach ($data as $key => $value) {
			if($key == count($data)-1) {
				$type = $value;
			} else {
				array_push($sid,$value);
			}
		}
		$sid = implode('-', $sid);
		
		$status = 1;
		if(strtolower($type) == 'qf') {
			$status = 3;
		}else if(strtolower($type) == 'tm') {
			$status = 2;
		}

		$survey = Survey::where('survey_uuid',$sid)->first();
		if(!empty($survey)) {
			$cookie_name = $survey->survey_cookie;
			$sid = Cookie::get($cookie_name.'-sid');
			$user_id = Cookie::get($cookie_name.'-uid');
			
			

			$st = SurveyTracking::where('survey_id',$sid)->where('user_id',$user_id)->first();
			
			$st->final_status = $status;
			$st->final_update_time = $current_time = date('Y-m-d H:i:s');
			$st->save();

			// If request come from publishers, we will hit on publishers
			$pid = Cookie::get($cookie_name.'-pid');
			
			$supcd ='';
			$sccode ='';
			if(!empty($pid)) {
				$pub = Publisher::where('p_uuid',$pid)->first();
				$pid = $pub->id;
				$supcd = $pub->supcode;	
				$sp = SurveyPublisher::where('survey_id',$survey->id)->where('publisher_id',$pid)->where('status',1)->first();
						
				if($supcd!=''){
					$supcode = Cookie::get($cookie_name.'-'.$supcd);	
					$lnk = '&'.$supcd.'='.$supcode;
				}
				

			if(!empty($sp)) {
				if($status == 1) {
				 // $this->hitOnPublishers($sp->link1);
				  if($sp->link1 != '' ){
				  	
				  	 if($supcd != '' ){
				  		$link1 = $sp->link1.$lnk;
					  }else{
					  		$link1 = $sp->link1;
					  }
				  	return redirect($link1);
				  }	else{
				  	return redirect('http://rmr-research-onlinepanel.com/survey/complete');
				  }
					
				} else if($status == 2) {
					// $this->hitOnPublishers($sp->link2);

					if($sp->link2 != '' ){
						
				  		if($supcd != '' ){
				  			$link2 = $sp->link2.$lnk;
						  }else{
						  	 $link2 = $sp->link2;
						  }

				  		return redirect($link2);
					}else{
					   return redirect('http://rmr-research-onlinepanel.com/survey/quotafull');
					}

				} else if($status == 3) {
				//	$this->hitOnPublishers($sp->link3);	
					if($sp->link3 != '' ){
				  		if($supcd != '' ){
				  			$link3 = $sp->link3.$lnk;
						  }else{
						  		$link3 = $sp->link3;
						  }

				  		return redirect($link3);
					}else{
					   return redirect('http://rmr-research-onlinepanel.com/survey/terminate');
					}

					
				   
				}
			}
				
			}

			return 'success';
		}
		
	}

	public function hitOnPublishers($endpoint) {
		try {
			$client = new \GuzzleHttp\Client();
		
			$client->request('GET', $endpoint, []);
			$statusCode = $response->getStatusCode();
			$content = $response->getBody();
			
			return;
		} catch (\GuzzleHttp\Exception\GuzzleException $e) {	
			return;	
		}
	}
}
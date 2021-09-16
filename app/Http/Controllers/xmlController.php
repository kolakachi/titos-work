<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use DB;
use Auth;
use Hash;
use session;
use File;

use App\Http\Controllers\xmlController;
use App\Models\Admin as admin;
use App\Models\Uploadd as uploadd;



class xmlController extends Controller
{
    public function createxml(Request $req){
        session_start();
        $myuser = \Session::get('logged');

        $datas=$req->all();

        $compname=$datas['compname'];
        $xmlfile=$datas['myfiles'];
        $fileExt = $xmlfile->extension();
        $dt = date('d-m-Y');

        if ($fileExt == "xml"){


		    $data = $req->input('myfiles');
			$xmlrealfile = $req->file('myfiles')->getClientOriginalName();
			$destination = base_path() . '/public/xmluploads';
			$req->file('myfiles')->move($destination, $xmlrealfile);



			$xmlDataString = file_get_contents(public_path('xmluploads/'.$xmlrealfile));
	        $xmlObject = simplexml_load_string($xmlDataString);
	                   
	        $json = json_encode($xmlObject);
	        $phpDataArray = json_decode($json, true); 

            $IdSegmentString = $this->getIdSegment($phpDataArray);
	        // $newxml = $phpDataArray->children() as $latestxml;

	        // $latestxml->Identification_segment->Registry_number="gncu12345";
            dd($phpDataArray);

        }else{
        	Session::flash('error', 'Please select an XML file');
			return back();	
        }
    }

    
}

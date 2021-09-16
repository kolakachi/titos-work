<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Auth;
use Hash;
use session;

use App\Http\Controllers\loginController;
use App\Models\Admin as admin;
use App\Models\Uploadd as uploadd;
use App\Models\IdenSeg as IdenSeg;
use App\Models\Contia as Contia;
use App\Models\BolSpec as BolSpec;



class loginController extends Controller
{
    
    	//Function for Administrator Login
    public function Adminlogin(Request $request){

		        $this->validate($request,[
		    		'username' => 'required',
		    		'password' => 'required',

		    	]); 
		    	
		    	$data=$request->all();
		        $username=$data['username'];
		        $password = $data['password'];
		        $pass = md5($password);

		        $myadmin = new admin;
		    	$myuser = $myadmin::all()->where('username','=',$username)->first();
		    	if($myuser){	
		    		if($myuser->password == $pass){
		    			session_start();
		    			$myuser = \Session::get('logged');


				        $alluploadd = new uploadd;
				        $sumofalluploadd = count($alluploadd::all());

				        $newviewupd = $alluploadd::all();

				        $admindata = array('myuser'=>$myuser,'sumofalluploadd'=>$sumofalluploadd,'newviewupd'=>$newviewupd);



		                // $admindata = array('myuser'=>$myuser);
		    			return view('dashboard',$admindata);
		    		}else{
		    			
		    			 return redirect()->intended('/');
		    		}

		    	}else{
		    		return redirect()->intended('/');
		    	}
    }

    public function uploadpage(){
        session_start();
        $myuser = \Session::get('logged');

        $alluploadd = new uploadd;
        $sumofuploadd = count($alluploadd::all());
        $newviewuploadd = $alluploadd::all();

        $admindata = array('myuser'=>$myuser,'sumofuploadd'=>$sumofuploadd);

        return view('upl',$admindata);
    }

     public function dashboardpg(){
        session_start();
        $myuser = \Session::get('logged');

        $alluploadd = new uploadd;
        $sumofuploadd = count($alluploadd::all());
        $newviewuploadd = $alluploadd::all();

        $alluploadd = new uploadd;
		$sumofalluploadd = count($alluploadd::all());
		$newviewupd = $alluploadd::all();
		$admindata = array('myuser'=>$myuser,'sumofalluploadd'=>$sumofalluploadd,'newviewupd'=>$newviewupd, 'sumofuploadd'=>$sumofuploadd);

        // $admindata = array('myuser'=>$myuser,'sumofuploadd'=>$sumofuploadd);

        return view('dashboard',$admindata);
    }

    

    public function FileUploads(Request $req){
        session_start();
        $myuser = \Session::get('logged');

        $datas=$req->all();

        $compname=$datas['compname'];
        $Termina=$datas['Termina'];
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
			$BolSpecificSegmentString = $this->getBolSpecificSegment($phpDataArray);

			$xmlString = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
							<twm_bol>';
			$xmlString .= $IdSegmentString;
			$xmlString .= $BolSpecificSegmentString;
			$xmlString .= "</twm_bol>";
			$fileName = "EditedXML.xml";

			return response()->view('xml-index', [
				'xml' => $xmlString
			])->header('Cache-Control', 'public')
			->header('Content-Description', 'File Transfer')
			->header('Content-Disposition', 'attachment; filename='. $fileName)
			->header('Content-Transfer-Encoding', 'binary');

			$xml = simplexml_load_string($xmlString);

			

			$response = \Response::create($xml, 200);
			$response->header('Content-Type', 'text/xml');
			$response->header('Cache-Control', 'public');
			$response->header('Content-Description', 'File Transfer');
			$response->header('Content-Disposition', 'attachment; filename=' .$fileName);
			$response->header('Content-Transfer-Encoding', 'binary');
			return $response;

	        // DB::insert('insert into uploadd(companyname,fileext,dateofupload,Terminalcode) values (?,?,?,?)',[$compname,$xmlrealfile,$dt,$Termina]);

	        // Session::flash('success', $xmlrealfile." ".'successfully uploaded');
			// return back();

        }else{
        	Session::flash('error', 'Please select an XML file');
			return back();	
        }

    }

	public function getIdSegment($phpDataArray){
        $IdSegment = $phpDataArray["Identification_segment"];
        $string = '<identification_segment>';
        $string .= '<registry_number>'. $IdSegment["Registry_number"] . '</registry_number>';

        $string .= '<date>'. $IdSegment["Date"] . '</date>';
        $string .= '<bol_reference>'. $IdSegment["Bol_reference"] . '</bol_reference>';
        
        $customString = '<customs_office_segment><code>' .$IdSegment["Customs_office_segment"]["code"] . '</code></customs_office_segment>';
        $string .= $customString;
        $string .= '</identification_segment>';

        return $string;


    }

	public function getBolSpecificSegment($phpDataArray){
		$bolSegment = $phpDataArray["Bol_specific_segment"];
        $string = '<bol_specific_segment>';
		$string .= '<line_number>'. $bolSegment["Line_number"] . '</line_number>';
		$string .= '<bol_nature>'. $bolSegment["Bol_Nature"] . '</bol_nature>';
		$string .= "<master_bol>
						<customs_office_code>XXX</customs_office_code>
						<registry_number>XXX</registry_number>
						<date_of_departure />
						<master_bol_reference>XXX</master_bol_reference>
					</master_bol>";
		$string .= "<unique_carrier_reference></unique_carrier_reference>
        			<total_number_of_containers>".$bolSegment['Total_number_of_containers']."</total_number_of_containers>
        			<total_gross_mass_manifested>".$bolSegment['Total_gross_mass_manifested']."</total_gross_mass_manifested>
        			<volume_in_cubic_meters>".$bolSegment['Volume_in_cubic_meters']."</volume_in_cubic_meters>";
		$string .= "<bol_type_segment>
						<code>".$bolSegment['Bol_type_segment']['code']."</code>
					</bol_type_segment>
					<exporter_segment>
						<code />
						<name>".$bolSegment['Exporter_segment']['Name']."</name>
						<address>".$bolSegment['Exporter_segment']['address']."</address>
					</exporter_segment>
					<consignee_segment>
						<code />
						<name>".$bolSegment['Consignee_segment']['Name']."</name>
						<address>".$bolSegment['Consignee_segment']['address']."</address>
					</consignee_segment>
					<notify_segment>
						<code />
						<name>".$bolSegment['Notify_segment']['Name']."</name>
						<address>".$bolSegment['Notify_segment']['address']."</address>
					</notify_segment>
					<place_of_loading>
						<code>".$bolSegment['Place_of_loading_segment']['code']."</code>
					</place_of_loading>
					<place_of_unloading>
						<destport_code>".$bolSegment['Place_of_unloading_segment']['code']."</destport_code>
						<terminal_operator>
							<terminalop_berthcode></terminalop_berthcode>
						</terminal_operator>
					</place_of_unloading>";
		$string .= "<freight_segment>
						<bol_freight_cost />
						<currency></currency>
						<indicator_segment />
					</freight_segment>
					<packages_segment>
						<package_type_code>".$bolSegment['Packages_segment']['Package_type_code']."</package_type_code>
						<number_of_packages>".$bolSegment['Packages_segment']['Number_of_packages']."</number_of_packages>
						<cargo_class_segment>
							<cargo_code></cargo_code>
							<cargo_qty></cargo_qty>
							<cargo_uom></cargo_uom>
							<cargo_freight />
						</cargo_class_segment>
						<shipping_marks />
						<goods_description>".$bolSegment['Goods_description']."</goods_description>
					</packages_segment>
					<customs_segment>
						<value />
						<currency />
					</customs_segment>
					<transport_segment>
						<value />
						<currency />
					</transport_segment>
					<insurance_segment>
						<value />
						<currency />
					</insurance_segment>
					<seals_segment>
						<number_of_seals>".$bolSegment['Seals_segment']['Number_of_seals']."</number_of_seals>
						<mark_of_seals>".$bolSegment['Seals_segment']['Marks_of_seals']."</mark_of_seals>
						<sealing_party_code>".$bolSegment['Seals_segment']['Sealing_party_code']."</sealing_party_code>
						<information_part_a>".$bolSegment['Information_part_a']."</information_part_a>
					</seals_segment>
					<operations_segment>
						<location_segment>
							<code>".$bolSegment['Operations_segment']['Location_segment']['code']."</code>
							<information />
						</location_segment>
					</operations_segment>";
		foreach($phpDataArray['Container'] as $container){
			$string .= "<container>
            				<reference>".$container['Reference']."</reference>
            				<number />
            				<type>".$container['Type']."</type>
            				<empty_full>".$container['Empty_full']."</empty_full>
            				<seals>".$container['Seals']."</seals>
            				<mark1>".$container['Marks1']."</mark1>
        					<mark2 />
            				<sealing_party>".$container['Sealing_party']."</sealing_party>
							<cargo_class_segment>
								<cargo_code>DCBA20CL</cargo_code>
								<cargo_freight></cargo_freight>
							</cargo_class_segment>
						</container>
						";
		}
		$string .= '</bol_specific_segment>
		';

		return $string;

	}


	public function allxml(){
        session_start();
        $myuser = \Session::get('logged');
        

        $alluploadd = new uploadd;
        $sumofalluploadd = count($alluploadd::all());

        $newviewupd = $alluploadd::all();

        $data = array('myuser'=>$myuser,'sumofalluploadd'=>$sumofalluploadd,'newviewupd'=>$newviewupd);

        return view('dashboard',$data);
    }

    
    public function Exportxml($id)
	    {
	        session_start();
        	$myuser = \Session::get('logged');

	        $user = DB::table('uploadd')->where('id', $id)->first();
			$newfilename = $user->fileext;

			$xmlDataString = file_get_contents(public_path('xmluploads/'.$newfilename));
	        $xmlObject = simplexml_load_string($xmlDataString);
	                   
	        $json = json_encode($xmlObject);
	        $phpDataArray = json_decode($json, true); 

	        

	         // dd($phpDataArray);

	        //INITIALIZING THE FIRST SEGMENT
	        $Iden_seg_reg_num = trim(json_encode($phpDataArray["Identification_segment"]["Registry_number"]),'"');
	        $Iden_seg_dated = trim(json_encode($phpDataArray["Identification_segment"]["Date"]),'"');
	        $Iden_seg_bol_ref = trim(json_encode($phpDataArray["Identification_segment"]["Bol_reference"]),'"');
	        $Iden_seg_custom_off_seg= trim(json_encode($phpDataArray["Identification_segment"]["Customs_office_segment"]["Code"]),'"');


	        // $user2 = DB::table('identification_segment')->where('xmlid', $id)->first();
	        // $xid = $user2->xmlid;

	        $user2 = DB::table('identification_segment')
                ->where('xmlid', $id)
                ->first();
	        
	       
	        if($user2){
	        	
	        	DB::update('update identification_segment set reg_number = ?,dated=?,bol_ref=?,custom_seg=? where xmlid = ?',[$Iden_seg_reg_num,$Iden_seg_dated,$Iden_seg_bol_ref,$Iden_seg_custom_off_seg,$id]);
	        }else{
	        	
	        	DB::insert('insert into identification_segment(xmlid, reg_number,dated,bol_ref,custom_seg) values (?,?,?,?,?)',[$id,$Iden_seg_reg_num,$Iden_seg_dated,$Iden_seg_bol_ref,$Iden_seg_custom_off_seg]);
	        }


	        //INITIALIZING THE THIRD SEGMENT
	        $contt=$phpDataArray["Container"];
			$x=count($contt);
				$rd = rand(10000,99999);
				for($i=0; $i < $x ; $i++) 
    				{
            			$ref1 = trim(json_encode($contt[$i]["Reference"]),'"');
		            	$num1 = trim(json_encode($contt[$i]["Number"]),'"');
						$typ1 = trim(json_encode($contt[$i]["Type"]),'"');
						$emp1 = trim(json_encode($contt[$i]["Empty_full"]),'"');
						$sea1 = trim(json_encode($contt[$i]["Seals"]),'"');
						$mar1 = trim(json_encode($contt[$i]["Marks1"]),'"');
						$mar2 = trim(json_encode($contt[$i]["Marks2"]),'"');
						$sea2 = trim(json_encode($contt[$i]["Sealing_party"]),'"');
					    
					
	        			DB::insert('insert into container(xmlid, Referencez,Numberz,Typez,Empty,Seals,Mark1,mark2,Sealing_party,uniq) values (?,?,?,?,?,?,?,?,?,?)',[$id,$ref1,$num1,$typ1,$emp1,$sea1,$mar1,$mar2,$sea2,$rd]);
	        			}

    				
	        
	        // $string = substr($dataList, 1, -1);
	        //INITIALIZING THE SECOND SEGMENT
	        $Bol_Line_number = trim(json_encode($phpDataArray["Bol_specific_segment"]["Line_number"]),'"');
	        $Bol_Previous_document_reference = trim(json_encode($phpDataArray["Bol_specific_segment"]["Previous_document_reference"]),'"');
	        $Bol_Bol_Nature = trim(json_encode($phpDataArray["Bol_specific_segment"]["Bol_Nature"]),'"');
	        $Bol_Unique_carrier_reference = trim(json_encode($phpDataArray["Bol_specific_segment"]["Unique_carrier_reference"]),'"');
	        $Bol_Total_number_of_containers = trim(json_encode($phpDataArray["Bol_specific_segment"]["Total_number_of_containers"]),'"');
	        $Bol_Total_gross_mass_manifested = trim(json_encode($phpDataArray["Bol_specific_segment"]["Total_gross_mass_manifested"]),'"');
	        $Bol_Volume_in_cubic_meters = trim(json_encode($phpDataArray["Bol_specific_segment"]["Volume_in_cubic_meters"]),'"');
	        $Bol_Bol_type_segment = trim(json_encode($phpDataArray["Bol_specific_segment"]["Bol_type_segment"]["Code"]),'"');
	        $Bol_Exporter_segment_code = trim(json_encode($phpDataArray["Bol_specific_segment"]["Exporter_segment"]["Code"]),'"');
	        $Bol_Exporter_segment_Name = trim(json_encode($phpDataArray["Bol_specific_segment"]["Exporter_segment"]["Name"]),'"');
	        $Bol_Exporter_segment_Addr = trim(json_encode($phpDataArray["Bol_specific_segment"]["Exporter_segment"]["Address"]),'"');
	        $Bol_Consignee_segment_code = trim(json_encode($phpDataArray["Bol_specific_segment"]["Consignee_segment"]["Code"]),'"');
	        $Bol_Consignee_segment_Name = trim(json_encode($phpDataArray["Bol_specific_segment"]["Consignee_segment"]["Name"]),'"');
	        $Bol_Consignee_segment_Addr = trim(json_encode($phpDataArray["Bol_specific_segment"]["Consignee_segment"]["Address"]),'"');
	        $Bol_Notify_segment_Code = trim(json_encode($phpDataArray["Bol_specific_segment"]["Notify_segment"]["Code"]),'"');
	        $Bol_Notify_segment_Name = trim(json_encode($phpDataArray["Bol_specific_segment"]["Notify_segment"]["Name"]),'"');
	        $Bol_Notify_segment_Addr = trim(json_encode($phpDataArray["Bol_specific_segment"]["Notify_segment"]["Address"]),'"');
	        $Bol_Place_of_loading_segment = trim(json_encode($phpDataArray["Bol_specific_segment"]["Place_of_loading_segment"]["Code"]),'"');
	        $Bol_Place_of_unloading_segment = trim(json_encode($phpDataArray["Bol_specific_segment"]["Place_of_unloading_segment"]["Code"]),'"');
	        $Bol_Packages_segment_code = trim(json_encode($phpDataArray["Bol_specific_segment"]["Packages_segment"]["Package_type_code"]),'"');
	        $Bol_Packages_segment_pkg = trim(json_encode($phpDataArray["Bol_specific_segment"]["Packages_segment"]["Number_of_packages"]),'"');
	        $Bol_Shipping_marks = trim(json_encode($phpDataArray["Bol_specific_segment"]["Shipping_marks"]),'"');
	        $Bol_Goods_description = trim(json_encode($phpDataArray["Bol_specific_segment"]["Goods_description"]),'"');
	        $B0l_Freight_segment_val = trim(json_encode($phpDataArray["Bol_specific_segment"]["Freight_segment"]["Value"]),'"');
	        $B0l_Freight_segment_curr = trim(json_encode($phpDataArray["Bol_specific_segment"]["Freight_segment"]["Currency"]),'"');
	        $Bol_Freight_segment_Indi_code = trim(json_encode($phpDataArray["Bol_specific_segment"]["Freight_segment"]["Indicator_segment"]["Code"]),'"');
	        $Bol_Customs_segment_val = trim(json_encode($phpDataArray["Bol_specific_segment"]["Customs_segment"]["Value"]),'"');
	        $Bol_Customs_segment_curr = trim(json_encode($phpDataArray["Bol_specific_segment"]["Customs_segment"]["Currency"]),'"');
	        $Bol_Transport_segment_val = trim(json_encode($phpDataArray["Bol_specific_segment"]["Transport_segment"]["Value"]),'"');
	        $Bol_Transport_segment_curr = trim(json_encode($phpDataArray["Bol_specific_segment"]["Transport_segment"]["Currency"]),'"');
	        $Bol_Insurance_segment_val = trim(json_encode($phpDataArray["Bol_specific_segment"]["Insurance_segment"]["Value"]),'"');
	        $Bol_Insurance_segment_curr = trim(json_encode($phpDataArray["Bol_specific_segment"]["Insurance_segment"]["Currency"]),'"');
	        $Bol_Seals_segment_noSeal = trim(json_encode($phpDataArray["Bol_specific_segment"]["Seals_segment"]["Number_of_seals"]),'"');
	        $Bol_Seals_segment_MarkSeal = trim(json_encode($phpDataArray["Bol_specific_segment"]["Seals_segment"]["Marks_of_seals"]),'"');
	        $Bol_Seals_segment_SealCode = trim(json_encode($phpDataArray["Bol_specific_segment"]["Seals_segment"]["Sealing_party_code"]),'"');
	        $Bol_Information_part_a = trim(json_encode($phpDataArray["Bol_specific_segment"]["Information_part_a"]),'"');
	        $Bol_Operations_segment_Code = trim(json_encode($phpDataArray["Bol_specific_segment"]["Operations_segment"]["Location_segment"]["Code"]),'"');
	        $Bol_Operations_segment_Info = trim(json_encode($phpDataArray["Bol_specific_segment"]["Operations_segment"]["Location_segment"]["Information"]),'"');


	      	$user1 = DB::table('bol_specific_segment')
                ->where('xmlid', $id)
                ->first();

	        if($user1){

	        	DB::update('update bol_specific_segment set Line_number=?,Previous_document_reference=?,Bol_Nature=?,Unique_carrier_reference=?,Total_number_of_containers=?,Total_gross_mass_manifested=?,Volume_in_cubic_meters=?,Bol_type_segment_code=?,Exporter_segment_code=?,Exporter_segment_name=?,Exporter_segment_addr=?,Consignee_segment_code=?,Consignee_name=?,Consignee_segment_addr=?,Notify_segment_code=?,Notify_segment_name=?,Notify_segment_addr=?,Place_of_loading_segment_code=?,Place_of_unloading_segment_code=?,Package_type_code=?,Number_of_packages=?,Shipping_marks=?,Goods_description=?,Freight_segment_val=?,Freight_segment_cur=?,Indicator_segment_code=?,Customs_segment_val=?,Customs_segment_cur=?,Transport_segment_val=?,Transport_segment_cur=?,Insurance_segment_val=?,Insurance_segment_cur=?,Number_of_seals=?,Marks_of_seals=?,Sealing_party_code=?,Information_part_a=?,Location_segment_code=?,Location_segment_info=? where xmlid = ?',[$Bol_Line_number,$Bol_Previous_document_reference,$Bol_Bol_Nature,$Bol_Unique_carrier_reference,$Bol_Total_number_of_containers,$Bol_Total_gross_mass_manifested,$Bol_Volume_in_cubic_meters,$Bol_Bol_type_segment,$Bol_Exporter_segment_code,$Bol_Exporter_segment_Name,$Bol_Exporter_segment_Addr,$Bol_Consignee_segment_code,$Bol_Consignee_segment_Name,$Bol_Consignee_segment_Addr,$Bol_Notify_segment_Code,$Bol_Notify_segment_Name,$Bol_Notify_segment_Addr,$Bol_Place_of_loading_segment,$Bol_Place_of_unloading_segment,$Bol_Packages_segment_code,$Bol_Packages_segment_pkg,$Bol_Shipping_marks,$Bol_Goods_description,$B0l_Freight_segment_val,$B0l_Freight_segment_curr,$Bol_Freight_segment_Indi_code,$Bol_Customs_segment_val,$Bol_Customs_segment_curr,$Bol_Transport_segment_val,$Bol_Transport_segment_curr,$Bol_Insurance_segment_val,$Bol_Insurance_segment_curr,$Bol_Seals_segment_noSeal,$Bol_Seals_segment_MarkSeal,$Bol_Seals_segment_SealCode,$Bol_Information_part_a,$Bol_Operations_segment_Code,$Bol_Operations_segment_Info,$id]);
	        }else{
	       
	        	DB::insert('insert into bol_specific_segment(xmlid,Line_number,Previous_document_reference,Bol_Nature,Unique_carrier_reference,Total_number_of_containers,Total_gross_mass_manifested,Volume_in_cubic_meters,Bol_type_segment_code,Exporter_segment_code,Exporter_segment_name,Exporter_segment_addr,Consignee_segment_code,Consignee_name,Consignee_segment_addr,Notify_segment_code,Notify_segment_name,Notify_segment_addr,Place_of_loading_segment_code,Place_of_unloading_segment_code,Package_type_code,Number_of_packages,Shipping_marks,Goods_description,Freight_segment_val,Freight_segment_cur,Indicator_segment_code,Customs_segment_val,Customs_segment_cur,Transport_segment_val,Transport_segment_cur,Insurance_segment_val,Insurance_segment_cur,Number_of_seals,Marks_of_seals,Sealing_party_code,Information_part_a,Location_segment_code,Location_segment_info) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',[$id,$Bol_Line_number,$Bol_Previous_document_reference,$Bol_Bol_Nature,$Bol_Unique_carrier_reference,$Bol_Total_number_of_containers,$Bol_Total_gross_mass_manifested,$Bol_Volume_in_cubic_meters,$Bol_Bol_type_segment,$Bol_Exporter_segment_code,$Bol_Exporter_segment_Name,$Bol_Exporter_segment_Addr,$Bol_Consignee_segment_code,$Bol_Consignee_segment_Name,$Bol_Consignee_segment_Addr,$Bol_Notify_segment_Code,$Bol_Notify_segment_Name,$Bol_Notify_segment_Addr,$Bol_Place_of_loading_segment,$Bol_Place_of_unloading_segment,$Bol_Packages_segment_code,$Bol_Packages_segment_pkg,$Bol_Shipping_marks,$Bol_Goods_description,$B0l_Freight_segment_val,$B0l_Freight_segment_curr,$Bol_Freight_segment_Indi_code,$Bol_Customs_segment_val,$Bol_Customs_segment_curr,$Bol_Transport_segment_val,$Bol_Transport_segment_curr,$Bol_Insurance_segment_val,$Bol_Insurance_segment_curr,$Bol_Seals_segment_noSeal,$Bol_Seals_segment_MarkSeal,$Bol_Seals_segment_SealCode,$Bol_Information_part_a,$Bol_Operations_segment_Code,$Bol_Operations_segment_Info]);
	        }
			


        $UploadInView = new uploadd;
        $UploadInViewSum = $UploadInView::all();

        $IdenSegInView = new IdenSeg;
        $IdenSegInViewSum = $IdenSegInView::all();

        $ContiaInView = new Contia;
        $ContiaInViewSum = $ContiaInView::all();

        $BolSpecInView = new BolSpec;
        $BolSpecInViewSum = $BolSpecInView::all();

        $data = array('myuser'=>$myuser,'UploadInViewSum'=>$UploadInViewSum,'IdenSegInViewSum'=>$IdenSegInViewSum,'ContiaInViewSum'=>$ContiaInViewSum,'BolSpecInViewSum'=>$BolSpecInViewSum);

	    // return view('viewnewxml',$data);

        			
				    $result=DB::table('identification_segment')->where('id', $id)->first();
					if($result>0){
					$xml = new DOMDocument("1.0");
					 
					// It will format the output in xml format otherwise
					// the output will be in a single row
					$xml->formatOutput=true;
					$fitness=$xml->createElement("identification_segment");
					$xml->appendChild($fitness);
					while($row=mysqli_fetch_array($result)){
					    $user=$xml->createElement("identification_segment");
					    $fitness->appendChild($user);
					     
					    $reg_number=$xml->createElement("reg_number", $row['reg_number']);
					    $user->appendChild($reg_number);
					     
					    $dated=$xml->createElement("dated", $row['dated']);
					    $user->appendChild($dated);
					     
					    $bol_ref=$xml->createElement("bol_ref", $row['bol_ref']);
					    $user->appendChild($bol_ref);
					     
					    $custom_seg=$xml->createElement("custom_seg", $row['custom_seg']);
					    $user->appendChild($custom_seg);
					  
					}
					//echo "<xmp>".$xml->saveXML()."</xmp>";
					$xml->save("public/report123.xml");
					}
		}
    }

    					
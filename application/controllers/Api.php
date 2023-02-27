	<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Api extends CI_Controller {

		function __construct()
		{
	        // Call the Model constructor
			parent::__construct();
			$this->load->helper('url');
			$this->load->model('users_model');
			$this->load->model('application_model');
			$this->load->model('section_model');
			$this->load->database();
		}


		public function index()
		{
			$instructions_array = array('dpgs' => base_url('api/dpgs'),
				'dpg/{application_id}' => base_url('api/dpg/{application_id}'),
			'nominees' => base_url('api/nominees'),
			'nominee{nominee}' => base_url('api/nominee/{nominee_id}'),);
			header('Content-Type: application/json; charset=utf-8');
			// echo json_encode($instructions_array);
			echo json_encode($instructions_array, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT), "\n";
			// echo json_encode($data_1, JSON_PRETTY_PRINT), "\n";

			
		}

		public function dpgs_old()
		{
			$all_response = $this->application_model->get_all_dpg_applications_response();
			$solution_name_id = 1;
			$aliases_id = 2;
			$short_description_id = 4;
			$website_id = 5;
			$license_id = 11;
			$licenseurl_id = 12;
			$sdg_id = 9;
			$solution_category_id = 3;
			$repositories_id = 6;
			$owner_name_id = 13;
			
			$all_dpgs_data = array();
			$current_application_id = 0;
			$single_dpg_array = array();
			$owner_array = array();
			foreach ($all_response as $key => $sar) {
				$total_keys = count($all_response); 
				if($key == $total_keys-1)
				{
					$next_key_id = $key;
				}else{
					$next_key_id = $key+1;
				}
				if($sar['question_id'] == $solution_name_id)
				{
					$single_dpg_array['id'] = $sar['application_id'];
					$single_dpg_array['name'] = $sar['answer'];
				}
				if($sar['question_id'] == $aliases_id)
				{
					$single_dpg_array['aliases'] = explode(',', $sar['answer']);
				}
				if($sar['question_id'] == $short_description_id)
				{
					$single_dpg_array['description'] = $sar['answer'];
				}
				if($sar['question_id'] == $website_id)
				{
					$single_dpg_array['website'] = $sar['answer'];
					$owner_array['website'] =$sar['answer'];
					$owner_array['org_type'] ="";
					$owner_array['contact_name'] = " " .$sar['fname']." " .$sar['lname']." ";
					$owner_array['contact_email'] =$sar['email'];	
				}
				if($sar['question_id'] == $license_id)
				{
					$single_dpg_array['license'] = array(array('spdx' => $sar['answer'],
						'licenseURL' => $all_response[$next_key_id]['answer']
				));
				}
				if($sar['question_id'] == $sdg_id)
				{
					$single_dpg_array['SDGs'] = array(array('SDGNames' => explode(',', $sar['answer']),
						'evidenceText' => $all_response[$next_key_id]['answer']
				));
				}
				if($sar['question_id'] == $solution_category_id)
				{
					$single_dpg_array['type'] = explode(',', $sar['answer']);
				}
				if($sar['question_id'] == $repositories_id)
				{
					$single_dpg_array['repositories'] = explode(',', $sar['answer']);
				}
				if($sar['question_id'] == $owner_name_id)
				{
					//array_push($owner_array, 'name'=>$sar['answer']);
					$owner_array['name'] =$sar['answer'];
				}
				if($key == $total_keys-1)
				{
					$single_dpg_array['organizations'] = array($owner_array);
				}
				$single_dpg_array['sectors'] = array();
				$single_dpg_array['stage'] = "DPG";
				$current_application_id = $sar['application_id'];
				if($current_application_id != $all_response[$next_key_id]['application_id'])
				{
					array_push($all_dpgs_data, $single_dpg_array);
				}
			}
			array_push($all_dpgs_data, $single_dpg_array);
			header('Content-Type: application/json; charset=utf-8');
			//echo json_encode($all_dpgs_data, JSON_UNESCAPED_SLASHES), "\n";
			// echo json_encode($all_dpgs_data);
			echo json_encode($all_dpgs_data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT), "\n";
		}


		public function dpgs()
		{
			$all_response = $this->application_model->get_all_dpg_applications_response();
			$all_dpgs_data = $this->arrange_required_data_for_api_bulk($all_response, "DPG");
			header('Content-Type: application/json; charset=utf-8');
			//echo json_encode($all_dpgs_data, JSON_UNESCAPED_SLASHES), "\n";
			// echo json_encode($all_dpgs_data);
			echo json_encode($all_dpgs_data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT), "\n";
		}

		public function nominees()
		{
			$all_response = $this->application_model->get_all_nominee_applications_response();

			$all_dpgs_data = $this->arrange_required_data_for_api_bulk($all_response, "nominee");

			header('Content-Type: application/json; charset=utf-8');
			//echo json_encode($all_dpgs_data, JSON_UNESCAPED_SLASHES), "\n";
			// echo json_encode($all_dpgs_data);
			echo json_encode($all_dpgs_data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT), "\n";
		}


		public function dpg($application_id)
		{
			$all_response = $this->application_model->get_application_response($application_id);
			if(count($all_response) > 0)
			{
				$single_dpg_array = $this->arrange_required_data_for_api($all_response, "DPG");
			}else{
				$single_dpg_array = array();
			}
			
			

			header('Content-Type: application/json; charset=utf-8');
			//echo json_encode($all_dpgs_data, JSON_UNESCAPED_SLASHES), "\n";
			// echo json_encode($all_dpgs_data);
			echo json_encode($single_dpg_array, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT), "\n";
		}

		public function github($application_id)
		{
			$all_response = $this->application_model->get_application_response_for_github($application_id);
			if(count($all_response) > 0)
			{
				$single_dpg_array = $this->arrange_required_data_for_api($all_response, "DPG");
			}else{
				$single_dpg_array = array();
			}
			
			

			header('Content-Type: application/json; charset=utf-8');
			//echo json_encode($all_dpgs_data, JSON_UNESCAPED_SLASHES), "\n";
			// echo json_encode($all_dpgs_data);
			echo json_encode($single_dpg_array, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT), "\n";
		}

		public function nominee($application_id)
		{
			$all_response = $this->application_model->get_nominee_application_response($application_id);

			if(count($all_response) > 0)
			{
				$single_dpg_array = $this->arrange_required_data_for_api($all_response, "nominee");
			}else{
				$single_dpg_array = array();
			}
			
			

			header('Content-Type: application/json; charset=utf-8');
			//echo json_encode($all_dpgs_data, JSON_UNESCAPED_SLASHES), "\n";
			// echo json_encode($all_dpgs_data);
			echo json_encode($single_dpg_array, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT), "\n";
		}

		public function arrange_required_data_for_api_bulk($all_response, $stage)
		{
			
			$solution_name_id = 1;
			$aliases_id = 2;
			$short_description_id = 4;
			$website_id = 5;
			$license_id = 11;
			$licenseurl_id = 12;
			$sdg_id = 9;
			$solution_category_id = 3;
			$repositories_id = 6;
			$owner_name_id = 13;

			//new fields
			$solutionNameId = 1;
			$AliasesId = 2;
			$solutionCategoriesId = 3;
			$shortDescriptionId = 4;
			$websiteId = 5;
			$repositoriesId = 6;
			$locationsId = 7;
			$openLicensingId = 11;
			$platformIndependenceId = 15;
			$documentationId = 17;
			$collectsNonPiiId = 18;
			$privacyApplicableLawsId = 20;
			$openStandardsBestPracticesId = 21;
			$privacyId = 22;
			$inappropriateIllegalContentId = 25;
			$protectionFromHarassmentId = 28;
			$bestPracticesId = 31;
			$generalInformationArray = array();
			$openLicensingArray = array();
			$platformIndependenceArray = array();
			$privacyArray = array();
			
			$all_dpgs_data = array();
			$current_application_id = 0;
			$single_dpg_array = array();
			$owner_array = array();
			foreach ($all_response as $key => $sar) {
				$total_keys = count($all_response);

			if($key == $total_keys-1)
				{
					$next_key_id = $key;
				}else{
					$next_key_id = $key+1;
				}
				if($next_key_id == $total_keys-1)
				{
					$further_next_key_id = $next_key_id;
				}else{
					$further_next_key_id = $next_key_id+1;
				}
				
				
				
				
				
				
				if($sar['question_id'] == $solution_name_id)
				{
					$single_dpg_array['id'] = $sar['application_id'];
					$single_dpg_array['name'] = $sar['answer'];
					$owner_array['name'] =$sar['answer'];
				}
				if($sar['question_id'] == $aliases_id)
				{
					$single_dpg_array['aliases'] = explode(',', $sar['answer']);
				}

				if($sar['question_id'] == $short_description_id)
				{
					$single_dpg_array['description'] = $sar['answer'];
				}
				if($sar['question_id'] == $website_id)
				{
					$single_dpg_array['website'] = $sar['answer'];
					$owner_array['website'] =$sar['answer'];
					$owner_array['org_type'] ="owner";
					$owner_array['contact_name'] = " " .$sar['fname']." " .$sar['lname']." ";
					$owner_array['contact_email'] =$sar['email'];
				}
				if($sar['question_id'] == $license_id)
				{
					/*
					$single_dpg_array['license'] = array(array('spdx' => $sar['answer'],
						'licenseURL' => $all_response[$next_key_id]['answer']
				));
				*/
				}
				if($sar['question_id'] == $sdg_id)
				{
						$sdgarray = explode(',', $sar['answer']);
					$temp_sdg_array = array();
					foreach ($sdgarray as $sdgkey => $sdgvalue) {
					    
					    $single_sdg_array = array('sdg' => $sdgvalue,
						'relevance' => $all_response[$next_key_id]['answer']);
						
						array_push($temp_sdg_array, $single_sdg_array);
					    
					}	
				$single_dpg_array['sdgs'] = $temp_sdg_array;
				}
				if($sar['question_id'] == $solution_category_id)
				{
					$single_dpg_array['categories'] = explode(',', $sar['answer']);
				}
				if($sar['question_id'] == $repositories_id)
				{
					// $single_dpg_array['repositories'] = explode(',', $sar['answer']);
				}
				if($sar['question_id'] == $owner_name_id)
				{
					//array_push($owner_array, 'name'=>$sar['answer']);
					
					$single_dpg_array['clearOwnership'] = array(array('clearOwnershipName'=> $sar['answer'],
						'clearOwnershipURL' => $all_response[$next_key_id]['answer']));
				}
				if($key == $total_keys-1)
				{
					// $single_dpg_array['organizations'] = array($owner_array);
				}
				//new data fields
				if($sar['question_id'] == $solutionNameId)
				{
					$generalInformationArray['solutionName'] =$sar['answer'];
				}
				if($sar['question_id'] == $AliasesId)
				{
					$generalInformationArray['aliases'] = explode(',', $sar['answer']);
				}
				if($sar['question_id'] == $solutionCategoriesId)
				{
					$generalInformationArray['solutionCategories'] = explode(',', $sar['answer']);
				}
				if($sar['question_id'] == $shortDescriptionId)
				{
					$generalInformationArray['shortDescription'] = $sar['answer'];
				}
				if($sar['question_id'] == $websiteId)
				{
					$generalInformationArray['website'] = $sar['answer'];
				}
				if($sar['question_id'] == $repositoriesId)
				{
					$generalInformationArray['website'] = explode(',', $sar['answer']);
					$single_dpg_array['repositories'] = array(array('name' => 'main',
						'url' => $sar['answer']));
				}
				if($sar['question_id'] == $locationsId)
				{
					$generalInformationArray['locations'] = array('developmentCountries' => explode(',', $sar['answer']),
						'deploymentCountries' => explode(',', $all_response[$next_key_id]['answer'])
				);
				$single_dpg_array['locations'] = array('developmentCountries' => explode(',', $sar['answer']),
						'deploymentCountries' => explode(',', $all_response[$next_key_id]['answer'])
				);
				}
				if($sar['question_id'] == $openLicensingId)
				{
					$openLicensingArray = explode(',', $sar['answer']);
					$final_license_array = array();
					foreach ($openLicensingArray as $key => $sl) {
						$larray = array("openLicense" => $sl,
							"openLicenseEvidenceURLs" => $all_response[$next_key_id]['answer']);
						array_push($final_license_array, $larray);
						
					}
					$single_dpg_array['openlicenses'] = $final_license_array;

					/*
					$openLicensingArray = array('licenses' => explode(',', $sar['answer']),
						'evidence' => $all_response[$next_key_id]['answer']
					);
					*/
				}
				if($sar['question_id'] == $platformIndependenceId)
				{
					if($sar['answer'] == "Yes")
					{
					    $platformIndependenceArray = array('isPlatformIndependent' => $sar['answer'],
						'openAlternatives' => explode(',', $all_response[$next_key_id]['answer']));
					}else{
					   $platformIndependenceArray = array('isPlatformIndependent' => $sar['answer'],
						'openAlternatives' => array()); 
					}
					
					
				}
				if($sar['question_id'] == $documentationId)
				{
					$single_dpg_array['documentation'] = $sar['answer'];
				}
				if($sar['question_id'] == $collectsNonPiiId)
				{
					/*
					$single_dpg_array['NonPII'] = array('collectsNonPII' => $sar['answer'],
						'nonPIIAccessMechanism' => $all_response[$next_key_id]['answer']);
						*/
						
					if($sar['answer'] == "Yes")
					{
					   $single_dpg_array['NonPII'] = array('collectsNonPII' => $sar['answer'],
						'nonPIIAccessMechanism' => $all_response[$next_key_id]['answer']);
					}else{
					   $single_dpg_array['NonPII'] = array('collectsNonPII' => $sar['answer'],
						'nonPIIAccessMechanism' => "");
					}
				}
				if($sar['question_id'] == $privacyApplicableLawsId)
				{
					$privacyArray['privacyCompliance'] =  $sar['answer'];

				}
				if($sar['question_id'] == 30)
				{
					$privacyArray['privacyComplianceURL'] =  $sar['answer'];
				} 
				
				if($sar['question_id'] == 32)
				{
					$single_dpg_array['deploymentOrganisations']=  explode(',', $sar['answer']);
				} 

				if($sar['question_id'] == 33)
				{
					$single_dpg_array['deploymentCountriesDepartments']=  explode(',', $sar['answer']);
				} 

				if($sar['question_id'] == 34)
				{
					$single_dpg_array['otherDeploymentOrganisations']=  explode(',', $sar['answer']);
				} 

				if($sar['question_id'] == 35)
				{
					$single_dpg_array['awardsReceived']=  explode(',', $sar['answer']);
				} 

				if($sar['question_id'] == $openStandardsBestPracticesId)
				{
					$single_dpg_array['openStandards'] = explode(',', $sar['answer']);
				}
				
				if($sar['question_id'] == $bestPracticesId)
				{
					$single_dpg_array['bestPractices'] = explode(',', $sar['answer']);
				}

				if($sar['question_id'] == $privacyId)
				{
					/*
					$single_dpg_array['dataPrivacySecurity'] = array('collectsPII' => $sar['answer'],
						'typesOfPIIDataCollected' => explode(',', $all_response[$next_key_id]['answer']),
						'dataPrivacySecurity' => $all_response[$further_next_key_id]['answer']
					);*/
					if($sar['answer'] == "PII data is NOT collected NOT stored and NOT distributed.")
					{
					    $single_dpg_array['dataPrivacySecurity'] = array('collectsPII' => $sar['answer'],
						'typesOfPIIDataCollected' => array(),
						'dataPrivacySecurity' => "");
					}else{
					   $single_dpg_array['dataPrivacySecurity'] = array('collectsPII' => $sar['answer'],
						'typesOfPIIDataCollected' => explode(',', $all_response[$next_key_id]['answer']),
						'dataPrivacySecurity' => $all_response[$further_next_key_id]['answer']);
					}
				}
				if($sar['question_id'] == $inappropriateIllegalContentId)
				{
					/*
					$single_dpg_array['userContent'] = array('contentManagement' => $sar['answer'],
						'contentTypes' => explode(',', $all_response[$next_key_id]['answer']),
						'contentManagementPolicy' => $all_response[$further_next_key_id]['answer']);
						*/
						
					if($sar['answer'] == "Content is NOT collected NOT stored and NOT distributed.")
					{
					    $single_dpg_array['userContent'] = array('contentManagement' => $sar['answer'],
						'contentTypes' => array(),
						'contentManagementPolicy' => "");
					}else{
					   $single_dpg_array['userContent'] = array('contentManagement' => $sar['answer'],
						'contentTypes' => explode(',', $all_response[$next_key_id]['answer']),
						'contentManagementPolicy' => $all_response[$further_next_key_id]['answer']);
					}
				}
				if($sar['question_id'] == $protectionFromHarassmentId)
				{
					/*
					$single_dpg_array['protectionFromHarassment'] = array('facilitatesUserInteraction' => $sar['answer'],
						'harassmentPolicy' => $all_response[$next_key_id]['answer']);
						*/
					if($sar['answer'] == "Yes")
					{
					    $single_dpg_array['protectionFromHarassment'] = array('facilitatesUserInteraction' => $sar['answer'],
						'harassmentPolicy' => $all_response[$next_key_id]['answer']);
					}else{
					   $single_dpg_array['protectionFromHarassment'] = array('facilitatesUserInteraction' => $sar['answer'],
						'harassmentPolicy' => "");
					}
						
						
				}
				$single_dpg_array['sectors'] = array();
				if($key == $total_keys-1)
				{
					// $single_dpg_array['generalInformation'] = $generalInformationArray;
					// $single_dpg_array['openLicensing'] = $openLicensingArray;
					//$single_dpg_array['platformIndependence'] = $platformIndependenceArray;
					//$single_dpg_array['organizations'] = array($owner_array);
					//$single_dpg_array['privacy'] = array($privacyArray);
				}
				//$single_dpg_array['sectors'] = array();
				$single_dpg_array['stage'] = $stage;
				$current_application_id = $sar['application_id'];

				$single_dpg_array['privacy'] = array($privacyArray);
				$single_dpg_array['organizations'] = array($owner_array);
				$single_dpg_array['platformIndependence'] = $platformIndependenceArray;



				if($current_application_id != $all_response[$next_key_id]['application_id'])
				{
					//handle blank values
    				if (!array_key_exists("aliases",$single_dpg_array))
                    {
                      $single_dpg_array['aliases'] = "";
                    }
                    if (!array_key_exists("deploymentOrganisations",$single_dpg_array))
                    {
                          $single_dpg_array['deploymentOrganisations'] = "";
                    }
                    if (!array_key_exists("deploymentCountriesDepartments",$single_dpg_array))
                    {
                          $single_dpg_array['deploymentCountriesDepartments'] = "";
                    }
                    if (!array_key_exists("otherDeploymentOrganisations",$single_dpg_array))
                    {
                          $single_dpg_array['otherDeploymentOrganisations'] = "";
                    }
                    if (!array_key_exists("awardsReceived",$single_dpg_array))
                    {
                          $single_dpg_array['awardsReceived'] = "";
                    }
                    
					
					array_push($all_dpgs_data, $single_dpg_array);
					$single_dpg_array = array();
				}

			}
			//handle blank values
    				if (!array_key_exists("aliases",$single_dpg_array))
                    {
                      $single_dpg_array['aliases'] = "";
                    }
                    if (!array_key_exists("deploymentOrganisations",$single_dpg_array))
                    {
                          $single_dpg_array['deploymentOrganisations'] = "";
                    }
                    if (!array_key_exists("deploymentCountriesDepartments",$single_dpg_array))
                    {
                          $single_dpg_array['deploymentCountriesDepartments'] = "";
                    }
                    if (!array_key_exists("otherDeploymentOrganisations",$single_dpg_array))
                    {
                          $single_dpg_array['otherDeploymentOrganisations'] = "";
                    }
                    if (!array_key_exists("awardsReceived",$single_dpg_array))
                    {
                          $single_dpg_array['awardsReceived'] = "";
                    }
			array_push($all_dpgs_data, $single_dpg_array);
			$single_dpg_array = array();
			
			return $all_dpgs_data;
		}



		public function arrange_required_data_for_api($all_response, $stage)
		{
			$solution_name_id = 1;
			$aliases_id = 2;
			$short_description_id = 4;
			$website_id = 5;
			$license_id = 11;
			$licenseurl_id = 12;
			$sdg_id = 9;
			$solution_category_id = 3;
			$repositories_id = 6;
			$owner_name_id = 13;

			//new fields
			$solutionNameId = 1;
			$AliasesId = 2;
			$solutionCategoriesId = 3;
			$shortDescriptionId = 4;
			$websiteId = 5;
			$repositoriesId = 6;
			$locationsId = 7;
			$openLicensingId = 11;
			$platformIndependenceId = 15;
			$documentationId = 17;
			$collectsNonPiiId = 18;
			$privacyApplicableLawsId = 20;
			$openStandardsBestPracticesId = 21;
			$privacyId = 22;
			$inappropriateIllegalContentId = 25;
			$protectionFromHarassmentId = 28;
			$bestPracticesId = 31;

			$current_application_id = 0;
			$single_dpg_array = array();
			$owner_array = array();
			$generalInformationArray = array();
			$openLicensingArray = array();
			$platformIndependenceArray = array();
			$privacyArray = array();
foreach ($all_response as $key => $sar) {
				$total_keys = count($all_response);

			if($key == $total_keys-1)
				{
					$next_key_id = $key;
				}else{
					$next_key_id = $key+1;
				}
				if($next_key_id == $total_keys-1)
				{
					$further_next_key_id = $next_key_id;
				}else{
					$further_next_key_id = $next_key_id+1;
				}
				if($sar['question_id'] == $solution_name_id)
				{
					$single_dpg_array['id'] = $sar['application_id'];
					$single_dpg_array['name'] = $sar['answer'];
					$owner_array['name'] =$sar['answer'];
				}
				if($sar['question_id'] == $aliases_id)
				{
					$single_dpg_array['aliases'] = explode(',', $sar['answer']);
				}

				if($sar['question_id'] == $short_description_id)
				{
					$single_dpg_array['description'] = $sar['answer'];
				}
				if($sar['question_id'] == $website_id)
				{
					$single_dpg_array['website'] = $sar['answer'];
					$owner_array['website'] =$sar['answer'];
					$owner_array['org_type'] ="owner";
					$owner_array['contact_name'] = " " .$sar['fname']." " .$sar['lname']." ";
					$owner_array['contact_email'] =$sar['email'];
				}
				if($sar['question_id'] == $license_id)
				{
					/*
					$single_dpg_array['license'] = array(array('spdx' => $sar['answer'],
						'licenseURL' => $all_response[$next_key_id]['answer']
				));
				*/
				}
				if($sar['question_id'] == $sdg_id)
				{
						$sdgarray = explode(',', $sar['answer']);
					$temp_sdg_array = array();
					foreach ($sdgarray as $sdgkey => $sdgvalue) {
					    
					    $single_sdg_array = array('sdg' => $sdgvalue,
						'relevance' => $all_response[$next_key_id]['answer']);
						
						array_push($temp_sdg_array, $single_sdg_array);
					    
					}	
				$single_dpg_array['sdgs'] = $temp_sdg_array;
				}
				if($sar['question_id'] == $solution_category_id)
				{
					$single_dpg_array['categories'] = explode(',', $sar['answer']);
				}
				if($sar['question_id'] == $repositories_id)
				{
					// $single_dpg_array['repositories'] = explode(',', $sar['answer']);
				}
				if($sar['question_id'] == $owner_name_id)
				{
					//array_push($owner_array, 'name'=>$sar['answer']);
					
					$single_dpg_array['clearOwnership'] = array(array('clearOwnershipName'=> $sar['answer'],
						'clearOwnershipURL' => $all_response[$next_key_id]['answer']));
				}
				if($key == $total_keys-1)
				{
					// $single_dpg_array['organizations'] = array($owner_array);
				}
				//new data fields
				if($sar['question_id'] == $solutionNameId)
				{
					$generalInformationArray['solutionName'] =$sar['answer'];
				}
				if($sar['question_id'] == $AliasesId)
				{
					$generalInformationArray['aliases'] = explode(',', $sar['answer']);
				}
				if($sar['question_id'] == $solutionCategoriesId)
				{
					$generalInformationArray['solutionCategories'] = explode(',', $sar['answer']);
				}
				if($sar['question_id'] == $shortDescriptionId)
				{
					$generalInformationArray['shortDescription'] = $sar['answer'];
				}
				if($sar['question_id'] == $websiteId)
				{
					$generalInformationArray['website'] = $sar['answer'];
				}
				if($sar['question_id'] == $repositoriesId)
				{
					$generalInformationArray['website'] = explode(',', $sar['answer']);
					$single_dpg_array['repositories'] = array(array('name' => 'main',
						'url' => $sar['answer']));
				}
				if($sar['question_id'] == $locationsId)
				{
					$generalInformationArray['locations'] = array('developmentCountries' => explode(',', $sar['answer']),
						'deploymentCountries' => explode(',', $all_response[$next_key_id]['answer'])
				);
				$single_dpg_array['locations'] = array('developmentCountries' => explode(',', $sar['answer']),
						'deploymentCountries' => explode(',', $all_response[$next_key_id]['answer'])
				);
				}
				if($sar['question_id'] == $openLicensingId)
				{
					$openLicensingArray = explode(',', $sar['answer']);
					$final_license_array = array();
					foreach ($openLicensingArray as $key => $sl) {
						$larray = array("openLicense" => $sl,
							"openLicenseEvidenceURLs" => $all_response[$next_key_id]['answer']);
						array_push($final_license_array, $larray);
						
					}
					$single_dpg_array['openlicenses'] = $final_license_array;

					/*
					$openLicensingArray = array('licenses' => explode(',', $sar['answer']),
						'evidence' => $all_response[$next_key_id]['answer']
					);
					*/
				}
				if($sar['question_id'] == $platformIndependenceId)
				{
					
					if($sar['answer'] == "Yes")
					{
					    $platformIndependenceArray = array('isPlatformIndependent' => $sar['answer'],
						'openAlternatives' => explode(',', $all_response[$next_key_id]['answer']));
					}else{
					   $platformIndependenceArray = array('isPlatformIndependent' => $sar['answer'],
						'openAlternatives' => array()); 
					}
				}
				if($sar['question_id'] == $documentationId)
				{
					$single_dpg_array['documentation'] = $sar['answer'];
				}
				if($sar['question_id'] == $collectsNonPiiId)
				{
					/*
					$single_dpg_array['NonPII'] = array('collectsNonPII' => $sar['answer'],
						'nonPIIAccessMechanism' => $all_response[$next_key_id]['answer']);
						*/
						
					if($sar['answer'] == "Yes")
					{
					   $single_dpg_array['NonPII'] = array('collectsNonPII' => $sar['answer'],
						'nonPIIAccessMechanism' => $all_response[$next_key_id]['answer']);
					}else{
					   $single_dpg_array['NonPII'] = array('collectsNonPII' => $sar['answer'],
						'nonPIIAccessMechanism' => "");
					}
				}
				if($sar['question_id'] == $privacyApplicableLawsId)
				{
					$privacyArray['privacyCompliance'] =  $sar['answer'];

				}
				if($sar['question_id'] == 30)
				{
					$privacyArray['privacyComplianceURL'] =  $sar['answer'];
				} 
				
				if($sar['question_id'] == 32)
				{
					$single_dpg_array['deploymentOrganisations']=  explode(',', $sar['answer']);
				} 

				if($sar['question_id'] == 33)
				{
					$single_dpg_array['deploymentCountriesDepartments']=  explode(',', $sar['answer']);
				} 

				if($sar['question_id'] == 34)
				{
					$single_dpg_array['otherDeploymentOrganisations']=  explode(',', $sar['answer']);
				} 

				if($sar['question_id'] == 35)
				{
					$single_dpg_array['awardsReceived']=  explode(',', $sar['answer']);
				} 

				if($sar['question_id'] == $openStandardsBestPracticesId)
				{
					$single_dpg_array['openStandards'] = explode(',', $sar['answer']);
				}
				
				if($sar['question_id'] == $bestPracticesId)
				{
					$single_dpg_array['bestPractices'] = explode(',', $sar['answer']);
				}

				if($sar['question_id'] == $privacyId)
				{
					/*
					$single_dpg_array['dataPrivacySecurity'] = array('collectsPII' => $sar['answer'],
						'typesOfPIIDataCollected' => explode(',', $all_response[$next_key_id]['answer']),
						'dataPrivacySecurity' => $all_response[$further_next_key_id]['answer']
					);
					*/
					if($sar['answer'] == "PII data is NOT collected NOT stored and NOT distributed.")
					{
					    $single_dpg_array['dataPrivacySecurity'] = array('collectsPII' => $sar['answer'],
						'typesOfPIIDataCollected' => array(),
						'dataPrivacySecurity' => "");
					}else{
					   $single_dpg_array['dataPrivacySecurity'] = array('collectsPII' => $sar['answer'],
						'typesOfPIIDataCollected' => explode(',', $all_response[$next_key_id]['answer']),
						'dataPrivacySecurity' => $all_response[$further_next_key_id]['answer']);
					}
				}
				if($sar['question_id'] == $inappropriateIllegalContentId)
				{
					/*
					$single_dpg_array['userContent'] = array('contentManagement' => $sar['answer'],
						'contentTypes' => explode(',', $all_response[$next_key_id]['answer']),
						'contentManagementPolicy' => $all_response[$further_next_key_id]['answer']);
						*/
						
					if($sar['answer'] == "Content is NOT collected NOT stored and NOT distributed.")
					{
					    $single_dpg_array['userContent'] = array('contentManagement' => $sar['answer'],
						'contentTypes' => array(),
						'contentManagementPolicy' => "");
					}else{
					   $single_dpg_array['userContent'] = array('contentManagement' => $sar['answer'],
						'contentTypes' => explode(',', $all_response[$next_key_id]['answer']),
						'contentManagementPolicy' => $all_response[$further_next_key_id]['answer']);
					}
						
						
						
				}
				if($sar['question_id'] == $protectionFromHarassmentId)
				{
					/*
					$single_dpg_array['protectionFromHarassment'] = array('facilitatesUserInteraction' => $sar['answer'],
						'harassmentPolicy' => $all_response[$next_key_id]['answer']);
						*/
						
					if($sar['answer'] == "Yes")
					{
					    $single_dpg_array['protectionFromHarassment'] = array('facilitatesUserInteraction' => $sar['answer'],
						'harassmentPolicy' => $all_response[$next_key_id]['answer']);
					}else{
					   $single_dpg_array['protectionFromHarassment'] = array('facilitatesUserInteraction' => $sar['answer'],
						'harassmentPolicy' => "");
					}
				}
				$single_dpg_array['sectors'] = array();
				if($key == $total_keys-1)
				{
					// $single_dpg_array['generalInformation'] = $generalInformationArray;
					// $single_dpg_array['openLicensing'] = $openLicensingArray;
					$single_dpg_array['platformIndependence'] = $platformIndependenceArray;
					$single_dpg_array['organizations'] = array($owner_array);
					$single_dpg_array['privacy'] = array($privacyArray);
				}
				//$single_dpg_array['sectors'] = array();
				$single_dpg_array['stage'] = $stage;
				$current_application_id = $sar['application_id'];

				} //foreach loop ends here
				
				if (!array_key_exists("aliases",$single_dpg_array))
                {
                      $single_dpg_array['aliases'] = "";
                }
                if (!array_key_exists("deploymentOrganisations",$single_dpg_array))
                {
                      $single_dpg_array['deploymentOrganisations'] = "";
                }
                if (!array_key_exists("deploymentCountriesDepartments",$single_dpg_array))
                {
                      $single_dpg_array['deploymentCountriesDepartments'] = "";
                }
                if (!array_key_exists("otherDeploymentOrganisations",$single_dpg_array))
                {
                      $single_dpg_array['otherDeploymentOrganisations'] = "";
                }
                if (!array_key_exists("awardsReceived",$single_dpg_array))
                {
                      $single_dpg_array['awardsReceived'] = "";
                }

				return $single_dpg_array;
		}
	}

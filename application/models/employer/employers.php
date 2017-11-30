<?php 
class Employers extends Model
{
	
	public function searchContractor($data,$page_number = 1)
	{
			$filtered_array = $this->cleararray($data);			
			$last = end($filtered_array);			
			$where ='';	
			foreach($filtered_array as $keys)
			{
				if(!empty($keys['value']))
					{
						if($keys['name'] == $last['name'])
						{
							if($keys['name'] == 'job_success' && $keys['value'] > 0)
							{
								$where .='job_success <= '.$keys['value'].'';
							}
							elseif($keys['name'] == 'last_login_time')
							{
								$where .= $keys['name'].' > DATE_SUB(NOW(), INTERVAL '.$keys['value'].')';
							}
							elseif($keys['name'] == 'hours_worked')
							{
								if(strpos($keys['value'], '-') !== false)
								{
									$range = explode('-',$keys['value']);
									$where .= '('.$keys['name'].' BETWEEN '.$range[0].' AND '.$range[1].')';
								}
								else
								{
									if( $keys['value'] == '-100')
										{
											$where .= $keys['name'].' < '.$keys['value'].'';
										}
									else
										{
											$where .= $keys['name'].' > '.$keys['value'].'';
										}
									
								}
							}
							elseif($keys['name'] == 'industries')
							{
								if(strpos($keys['value'], ',') !== false)
								{
									$industries = explode(',',$keys['value']);									
									$emptyRemoved = $this->Arrayfilter($industries);									
									$like ='';
									$industries_last = end($emptyRemoved);									
									foreach($emptyRemoved as $ind_val)
									{
										if($industries_last == $ind_val)
										{
											$like .= 'industries LIKE "%'.$ind_val.'%"';
										}
										else
										{
											$like .= 'industries LIKE "%'.$ind_val.'%" OR ';
										}
										
									}
								$where .= "( $like )";	
								
									
								}								
							}
							elseif($keys['name'] == 'languages')
							{
								if(strpos($keys['value'], ',') !== false)
								{
									$languages = explode(',',$keys['value']);									
									$emptylanguages = $this->Arrayfilter($languages);									
									$like ='';
									$industries_last = end($emptylanguages);									
									foreach($emptylanguages as $ind_val)
									{
										if($industries_last == $ind_val)
										{
											$like .= 'languages LIKE "%'.$ind_val.'%"';
										}
										else
										{
											$like .= 'languages LIKE "%'.$ind_val.'%" OR ';
										}
										
									}
								$where .= "( $like )";	
								
									
								}								
							}
							elseif (strpos($keys['value'], '-') !== false) 
							{
								$range = explode('-',$keys['value']);
								$where .= '('.$keys['name'].' BETWEEN '.$range[0].' AND '.$range[1].')';
							}
							elseif($keys['name'] == 'hourly_wages')
							{
								$separator = explode(',',$keys['value']);
								$where .= '('.$keys['name'].' BETWEEN '.$separator[0].' AND '.$separator[1].')';						
							}
							else
							{
								$where .= $keys['name'].' LIKE "%'.$keys['value'].'%"';
							}
						}
						else
						{
							if($keys['name'] == 'job_success' && $keys['value'] > 0)
							{
								$where .='job_success <= '.$keys['value'].' AND ';
							}
							elseif($keys['name'] == 'last_login_time')
							{
								$where .= $keys['name'].' > DATE_SUB(NOW(), INTERVAL '.$keys['value'].') AND ';
							}
							elseif($keys['name'] == 'hours_worked')
							{
								if(strpos($keys['value'], '-') !== false)
								{
									$range = explode('-',$keys['value']);
									$where .= '('.$keys['name'].' BETWEEN '.$range[0].' AND '.$range[1].') AND ';
								}
								else
								{
									if( $keys['value'] == '-100')
										{
											$where .= $keys['name'].' < '.$keys['value'].' AND ';
										}
									else
										{
											$where .= $keys['name'].' > '.$keys['value'].' AND ';
										}
									
								}
							}
							elseif($keys['name'] == 'industries')
							{
								if(strpos($keys['value'], ',') !== false)
								{
									$industries = explode(',',$keys['value']);										
									$emptyRemoved = $this->Arrayfilter($industries);
									$like ='';
									$industries_last = end($emptyRemoved);
									
									foreach($emptyRemoved as $ind_val)
									{
										if($industries_last == $ind_val)
										{
											$like .= 'industries LIKE "%'.$ind_val.'%"';
										}
										else
										{
											$like .= 'industries LIKE "%'.$ind_val.'%" OR ';
										}
										
									}
								$where .= "( $like ) AND ";		
								
									
								}								
							}
							elseif($keys['name'] == 'languages')
							{
								if(strpos($keys['value'], ',') !== false)
								{
									$industries = explode(',',$keys['value']);										
									$emptyRemoved = $this->Arrayfilter($industries);
									$like ='';
									$industries_last = end($emptyRemoved);
									
									foreach($emptyRemoved as $ind_val)
									{
										if($industries_last == $ind_val)
										{
											$like .= 'languages LIKE "%'.$ind_val.'%"';
										}
										else
										{
											$like .= 'languages LIKE "%'.$ind_val.'%" OR ';
										}
										
									}
								$where .= "( $like ) AND ";		
								
									
								}								
							}							
							elseif (strpos($keys['value'], 	'-') !== false) 
							{
								$range = explode('-',$keys['value']);
								$where .= '('.$keys['name'].' BETWEEN '.$range[0].' AND '.$range[1].') AND ';
							}
							elseif($keys['name'] == 'hourly_wages')
							{
								$separator = explode(',',$keys['value']);
								$where .= '('.$keys['name'].' BETWEEN '.$separator[0].' AND '.$separator[1].') AND ';						
							}
							else
							{
								$where .= $keys['name'].' LIKE "%'.$keys['value'].'%" AND ';
							}
						}
						
					}		
			}
			/* echo $where; */
				
			//return $search = $this->custom_where($where,PREFIX.'contractor_profile');
			/* echo"<pre>";
			 print_r($search);
			echo"</pre>"; */
			$item_per_page = 2;			
			$position = (($page_number-1) * $item_per_page);
			
			$res = $this->getContractors($position, $item_per_page, $where);
			$array = json_decode($res);		
			return json_encode(array( 'content'=>$array->content, 'count'=>$array->count, 'page_number'=>$page_number ));
			
	}
	
	private function cleararray($data)
	{
			$filtered_array = array();
			foreach($data as $keys => $values)
			{
				if(!empty($values['value']))
				{
					$filtered_array[] = $data[$keys] ;
				}			
			}
		return	$filtered_array;
	}
	
	private function Arrayfilter($data)
	{
		$arr = array();		
		$linksArray = array_filter($data);
		foreach($linksArray as $keys)
		{
			if(!empty($keys) && $keys !=' ')
			{
				$arr[] = trim($keys);
			}			
		}		
		return $arr;
	}

	public function getLocations()
	{
		$this->query("SELECT * FROM flex_states WHERE `country_id` = '231' || `country_id` = '38'");
		return $result = $this->resultset();
	}
	
	public function jobActivityStatus($job_id , $status)
	{
		$this->query("SELECT * FROM flex_job_activities WHERE `job_id` = ".$job_id." AND `job_status` = $status");
		return $result = $this->resultset();
	}
	
	public function getContractors($position, $item_per_page, $where = NULL)
	{
		$html ='';
		if(empty($where)):
			$this->query("SELECT * FROM ".PREFIX."contractor_profile ORDER BY id ASC LIMIT $position, $item_per_page");
		else:
			$this->query("SELECT * FROM ".PREFIX."contractor_profile WHERE $where ORDER BY id ASC LIMIT $position, $item_per_page");		
		endif;
		$results = $this->resultset();
		
		if(!empty($where)):
			$Count = $this->getTotalContractor( $where );
		else:
			$Count = 0;
		endif;
		
		/* $this->query("SELECT count(*) as Count FROM ".PREFIX."contractor_profile");		
		$ContractorCount = $this->resultset();
		$pages = ceil($ContractorCount[0]["Count"]/$item_per_page);			 */	
			
		 foreach($results as $keys)
			{
				$industries = explode(',',$keys['industries']);
				$ind ='';
				foreach($industries as $inkeys)
				{
					$ind .='<span class="industry-tag">'.$inkeys.'</span>';
				}
				if(!empty($keys["profile_img"]))
				{
					$img = BASE_URL.'static/images/contractor/'.$keys["profile_img"];
				}
				else
				{
					$img = BASE_URL.'static/images/contracts-user-img.jpg';
				}
				
				$html .='<article class="contractor-box">
				  <figure class="contractor-avatar">
					<img src="'.$img.'" alt="Contractor Picture"> </figure>
					<div class="contractor-action">
					<div class="dropdown">
							<button class="btn btn-blue dropdown-toggle" type="button" id="actions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Actions <i class="fa fa-angle-down" aria-hidden="true"></i> </button>
							<ul class="dropdown-menu blue animated fast fadeInUpSmall" aria-labelledby="actions">
							  <li><a href="#">Invite</a></li>
							  <li><a href="#">Save</a></li>
							</ul>
						  </div>
					</div>
					
					<div class="contractor-pro-details"><h3><a href="#">Lorem ipsum</a></h3>
					<p>Lorem ipsum/ Dummy  heading</p>
					<p><strong>Hourly wage:</strong> $'.$keys["hourly_wages"].'/hour <span class="sep">I</span> <strong>Hours worked:</strong> '.$keys["hours_worked"].' hours <span class="sep">I</span> <strong>Job Success:</strong> '.$keys["job_success"].'%</p></div>
					<div class="contractror-descrip">
					<h3>Description:</h3>
					<p>'.$keys["description"].'</p> 
					</div>
					<div class="contractor-training">
					<h3>Training completed:</h3>
					<p class="pro-industries"><span class="industry-tag">Health care industrie</span> <span class="industry-tag">Mechanical industrie</span> <span class="industry-tag">Automobile industrie</span></p>
					</div>
					<div class="contractor-training">
					<h3>Industry Expertise:</h3>					
					<p class="pro-industries">'.$ind.'</p>
					</div>
				  </article>';
			} 
		
		return json_encode(array( 'content'=>$html,'count'=>$Count));		
	}


	public function getTotalContractor($where = NULL)
	{
		if(!empty($where))
		{
			$this->query("SELECT * FROM ".PREFIX."contractor_profile WHERE $where");			
		}
		else
		{
			$this->query("SELECT * FROM ".PREFIX."contractor_profile");
		}
		$this->resultset();
		return $this->rowCount();
	}
}


<?php 
class Page extends Model
{
	private $table = 'flex_pages';
	
	/*public function __construct()
	{
		$this->table = PREFIX.'pages';
	}
	*/
	
	/* Get All the Page */
	public function getAll()
	{
		//$data = $this->get_all($this->table);
		$this->query("SELECT * FROM $this->table WHERE status != 'Trash'");
		$data = $this->resultset();
		$table = "";
		$table .= ' <table width="100%" class="table table-striped table-bordered table-hover dataTables">';		
		$table .= '<thead>
					<tr>
						<th>Title</th>
						<th>Slug</th>
						<th>Status</th>														
						<th>Actions</th>														
					</tr>
				</thead>';	
		$table .='<tbody>';	
		foreach($data as $keys):
			$table .='<tr>';
					$table .='<td>'.$keys["title"].'</td>';
					$table .='<td><a href="'.SITEURL.$keys["slug"].'" target="_blank">'.$keys["slug"].'</a></td>';
					$table .='<td>'.$keys["status"].'</td>';					
					$table .='<td><a class="btn btn-primary btn_edit" href="'.SITEURL.'admin/pages/editPage/'.$keys["id"].'">Edit</a>
					<button id="'.$keys["id"].'" class="btn btn-danger Del_page">Trash</button>
					<a href="'.SITEURL.$keys["slug"].'" class="btn btn-success" target="_blank">View</a>
					</td>';					
			$table .='</tr>';
			
		endforeach;			
		return $table;
		
	}
	
	/* Get Trash Page */
	public function getTrashs()
	{
	
		$this->query("SELECT * FROM $this->table WHERE `status` = 'Trash'");
		$data = $this->resultset();		
		$table = "";
		$table .= ' <table width="100%" class="table table-striped table-bordered table-hover dataTables">';		
		$table .= '<thead>
					<tr>
						<th>Title</th>
						<th>Slug</th>
						<th>Status</th>														
						<th>Actions</th>														
					</tr>
				</thead>';	
		$table .='<tbody>';	
		foreach($data as $keys):
			$table .='<tr>';
					$table .='<td>'.$keys["title"].'</td>';
					$table .='<td><a href="'.SITEURL.$keys["slug"].'" target="_blank">'.$keys["slug"].'</a></td>';
					$table .='<td>'.$keys["status"].'</td>';					
					$table .='<td><button id="'.$keys["id"].'"class="btn btn-danger Del_page">Delete</button>
					<button id="'.$keys["id"].'"class="btn btn-primary Res_page">Restore</button>
					</td>';					
			$table .='</tr>';
			
		endforeach;			
		return $table;
	}
	
	
	/* Trash The Page */
	public function trashPage($pageID)
	{
		$data = array(
		'status'=>"Trash",		
		'modifiedOn'=>$this->Timestamp(),
		);
		$this->update($data,'id',$pageID,$this->table);
		return $this->rowCount();
	}
	
	/* Restore Trash Page */
	public function RestorePage($pageID)
	{
		$data = array(
		'status'=>"publish",		
		'modifiedOn'=>$this->Timestamp(),
		);
		$this->update($data,'id',$pageID,$this->table);
		return $this->rowCount();
	}
	
	/* Delete Page Data */
	public function removePageData($pageID)
	{
		$this->delete_all_record($this->table , 'id' , $pageID);
		return $this->rowCount();		
	}
	
	/* Insert Page */
	public function SavePage($content)
	{
		
		$Cslug = $this->slugify($content['page_title']);
		$slug = $this->CreateSlug($Cslug);
		$image ="";
		if(!empty($content['banner_image']["name"]))
		{
			$image = $this->UploadImage($content['banner_image']);			
		}
		
		$data = array(
		'title'=>$content['page_title'],
		'content'=>$content['content'],
		'tag_line'=>$content['tag_line'],
		'tag_line_float'=>$content['banner_float'],
		'banner_image'=>$image,
		'slug'=>$slug,
		'status'=>$content['status'],
		'createdOn'=>$this->Timestamp(),
		'modifiedOn'=>$this->Timestamp(),
		);
		$ID = $this->Insert($data,$this->table);
		Return $ID;
		
	}
	
	/* Update Page */
	
	public function UpdatePage($content,$ID)
	{
		if(isset($content['preloaded']))
		{
			$image = $content['preloaded'];			
		}
		
		if(!empty($content['banner_image']["name"]))
		{
			$image = $this->UploadImage($content['banner_image']);			
		}
		
		$data = array(
		'title'=>$content['page_title'],
		'content'=>$content['content'],
		'tag_line'=>$content['tag_line'],
		'tag_line_float'=>$content['banner_float'],
		'banner_image'=>$image,
		'status'=>$content['status'],		
		'modifiedOn'=>$this->Timestamp(),
		);
		$this->update($data,'id',$ID,$this->table);
		return $this->rowCount();
	}
	
	
	/* Check Slug Exists */	
	public function checkSlug($slug)
	{
		$path = APP_DIR . 'controllers/' . $slug . '.php';
		if(file_exists($path)){
			return  "Invalid";
		}
		else
		{
			return "valid";
		}			
	}
	
	/* Create a Slug For Page */
	public function CreateSlug($pageTitle)
	{
		$slug = str_replace(' ','-',$pageTitle);
		$check = $this->checkSlug($slug);
		
		if($check == 'valid')
		{
			return $this->uniqueSlug($slug);
		}
		else
		{
			return $this->uniqueSlug($slug);
		}			
	}
	
	/* Get slugs from DB */
	
	private function getSlug($slug)
	{
		$check = $this->get_single_row('slug',$slug,$this->table);
		if(empty($check))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	private function uniqueSlug($slug)
	{
		$i = 1; $baseSlug = $slug;
		while($this->getSlug($slug)){
			$slug = $baseSlug . "-" . $i++;        
		}
		return $slug;
	}
	
	public function getPageList()
	{
		/* For Menu to show Pages as check box */
		$this->query("SELECT * FROM $this->table WHERE status != 'Trash' AND status != 'Draft'");
		$data = $this->resultset();
		$li = "";
		foreach( $data as $keys ):
		$url = BASE_URL.$keys["slug"];
		$li .='<div class="form-check">
				<label class="form-check-label">
				  <input type="checkbox" value="'.$keys["title"].'" url="'.$url.'" class="form-check-input page_li">
				  '.$keys["title"].'
				</label>
			  </div>';			
		endforeach;
		$li .= '<button type="submit" class="btn btn-primary page_menu">Add To Menu</button>';
		return $li;
		
	}
	
	
	public function UploadImage($content)
	{
		$target_dir = ROOT_DIR."/static/uploads/pages/";
		$filename = basename($content["name"]);
		$target_file = $target_dir . $filename;
		$i = 1;
		while(file_exists($target_file))
		{
			$imG = explode('.',$content["name"]);
			$filename = basename($imG[0]).$i.'.'.$imG[1];
			$target_file = $target_dir . $filename;
			$i++;
		}
		
		
		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		// Check if image file is a actual image or fake image
		
		$check = getimagesize($content["tmp_name"]);
			if($check !== false) {
				echo "File is an image - " . $check["mime"] . ".";
				$uploadOk = 1;
			} else {
				echo "File is not an image.";
				$uploadOk = 0;
			}
		
		
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" )
		{
			echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
			$uploadOk = 0;
		}
		
		if ($uploadOk == 0) 
		{
			echo "Sorry, your file was not uploaded.";
	
		} 
		else 
		{
			if (move_uploaded_file($content["tmp_name"], $target_file)) 
			{
				return $filename;
			} 
			else
			{
				echo "Sorry, there was an error uploading your file.";
			}
		}



	}
	
}
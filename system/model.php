<?php
class Model
{
	
  private $host = 'localhost';
  private $dbName = 'imarkcli_forceflexing';
  private $user = 'imarkcli_forcefl';
  private $pass = 'e8m]~$wfz@3Z';
     
  private $dbh;
  private $error;
  private $qError;
  
  private $stmt;
  
  public function __construct()
  {
      //dsn for mysql
    $dsn = "mysql:host=".$this->host.";dbname=".$this->dbName;
    $options = array(
        PDO::ATTR_PERSISTENT    => true,
        PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION
        );
    
    try
	{
       $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
    }
    //catch any errors
    catch (PDOException $e)
	{
        $this->error = $e->getMessage();
    }
    
  }
  
  public function query($query)
  {
      $this->stmt = $this->dbh->prepare($query);
  }
  
  public function bind($param, $value, $type = null)
  {
    if(is_null($type))
	{
		switch (true)
		{
            case is_int($value):
				$type = PDO::PARAM_INT;
            break;
            case is_bool($value):
                $type = PDO::PARAM_BOOL;
            break;
            case is_null($value):
                $type = PDO::PARAM_NULL;
                break;
            default:
                $type = PDO::PARAM_STR;
        }
    }
    $this->stmt->bindValue($param, $value, $type);
  }
  
  public function execute()
  {
	return $this->stmt->execute();
	$this->qError = $this->dbh->errorInfo();
	if(!is_null($this->qError[2]))
	{
		echo $this->qError[2];
	}
	echo 'done with query';
  }
  
  public function resultset()
  {
    $this->execute();
    return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  
  public function single()
  {
    $this->execute();
    return $this->stmt->fetch(PDO::FETCH_ASSOC);
  }
  
  public function rowCount()
  {
    return $this->stmt->rowCount();
  }
  
  public function lastInsertId()
  {
    return $this->dbh->lastInsertId();
  }
  
  public function beginTransaction()
  {
    return $this->dbh->beginTransaction();
  }
  
  public function endTransaction()
  {
    return $this->dbh->commit();
  }
  
  public function cancelTransaction()
  {
    return $this->dbh->rollBack();
  }
  
  public function debugDumpParams()
  {
    return $this->stmt->debugDumpParams();
  }
  
  public function queryError()
  {
	$this->qError = $this->dbh->errorInfo();
	if(!is_null($qError[2]))
	{
		echo $qError[2];
	}
  }
  
  public function Insert($data,$table)
	{
		/* Insert function To insert data to desired table */
		$keys ='';	$values ='';
		$total = count($data);
		$x = 1;
		foreach($data as $key => $val)
		{
			if($x==$total)
				{	
					$keys .=$key; $values .=':'.$key;		
				}
			else
				{	
					$keys .=$key.','; $values .=':'.$key.',';		
				}
			$x++;
		}	
		$this->beginTransaction();	
		$this->query("INSERT INTO $table ($keys) VALUES($values)");
		foreach($data as $ke => $vals)
		{
			$this->bind(':'.$ke.'',$vals);
		}
		$this->execute();	
		$last_id = $this->lastInsertId();	// Get last Inserted Record Id
			$this->cancelTransaction();	
			return $last_id;
		
	
	}
	
	//fetch row from desired table
	public function get_single_row($wherekey, $whereval,$table)
	{
		$query=$this->query("SELECT * FROM $table  WHERE  $wherekey = :id");
	    $this->bind(':id',$whereval);
		return $row= $this->single();
	}
	
	//get all data from table
	public function get_all($table)
	{
		$this->query("SELECT * FROM $table");
		return $result = $this->resultset();
	}
	//get the table record with simple where condition
	public function get_table_data($table,$field,$value,$order="DESC")
	{
		if($table == "flex_roles")
			$orderby="roleid";
		else
			$orderby="id";
		$this->query("SELECT * FROM `".$table."` WHERE `".$field."` = '".$value."' order by ".$orderby." ".$order."");
		return $result = $this->resultset();
	}
	
	public function custom_where($where,$table)
	{
		$this->query("SELECT * FROM `".$table."` WHERE $where");
		return $result = $this->resultset();
	}
	
	public function get_record_count($table,$field,$value)
	{
		$this->query("SELECT * FROM $table WHERE `$field` = '".$value."'");
		$result = $this->resultset();
		return $testing = $this->rowCount();
	}
	public function get_record_count1($table,$field,$value)
	{
		$this->query("SELECT * FROM $table WHERE `$field` Like '%".$value."%'");
		$result = $this->resultset();
		return $testing = $this->rowCount();
	}
			
	//update data for a table
	public function update($data,$wherekey,$whereval,$table)
	{
		/* update function */
		$updata ='';	
		$total = count($data);
		$x = 1;
		foreach($data as $key => $val)
		{
			if($x==$total)
				{	
					$updata .=$key."='". $val."'"; 	
				}
			else
				{	
					$updata .=$key."='". $val. "',"; 
				}
			$x++;
		}
		$query='UPDATE '.$table.' SET ' .$updata.' WHERE '.$wherekey.' = "'.$whereval.'"';
		$this->beginTransaction();	
		$this->query($query);
		$this->execute();
		$this->endTransaction();			
	}
	
	//fetch a particular column
	public function get_single_row_columns($column,$wherekey, $whereval,$table)
	{
	
		if(is_array($column))
			$columns=implode(",",$column);
		else
			$columns=$column;
		$query=$this->query("SELECT $columns FROM $table  WHERE  $wherekey = :id");
	
	    $this->bind(':id',$whereval);
		return $row= $this->single();
	}

	public function get_single_row_columns1($column,$wherekey, $whereval,$table)
	{
		$query=$this->query("SELECT $column FROM $table  WHERE  $wherekey = $whereval");
		return $row= $this->resultset();
	}
	public function get_Double_row_columns($column,$wherekey1, $whereval1,$wherekey, $whereval,$table)
	{
		$query=$this->query("SELECT $column FROM $table  WHERE  `$wherekey` = '$whereval' AND `$wherekey1` = '$whereval1'");
		return $row= $this->resultset();
	}
	
	public function login_user($email)
	{
		$login_user_data=$this->get_single_row('username',$email,PREFIX.'users');
		return $login_user_data;
	}
	
	public function get_filterjob($table, $searchItem , $userId , $jobType , $fixedRange , $hourlyRange , $experianceLevel , $travelDistance , $projectDuration)
	{
		$string="";
		if ($searchItem != "") 
		{
			$string .= "`job_title`  LIKE '%".$searchItem."%' OR `job_description` LIKE '%".$searchItem."%'"."&&  ";
		}
		if ($jobType != "") 
		{
			$string .= "`job_type` = '".$jobType."' &&";
		}
		$string.= "`job_visibility` = 'anyone'";
		$qry="SELECT * FROM $table where ".$string."";
		$this->query($qry);
		return $result = $this->resultset();	
		//return $table.$searchItem.$userId.$jobType.$fixedRange.$hourlyRange.$experianceLevel.$travelDistance.$projectDuration;
	}
	public function data_filter($query)
	{
		$this->query($query);
		return $result = $this->resultset();	
	}
	
	public function get_all_mul_cond($where_cond,$table,$order)
	{
		$where ='';	
		$total = count($where_cond);
		$x = 1;
		foreach($where_cond as $key => $val)
		{
			if($x==$total)
				{	
					$where .=$key."='". $val."'"; 	
				}
			else
				{	
					$where .=$key."='". $val. "' and "; 
				}
			$x++;
		}
		if($table == "flex_roles")
			$orderby="roleid";
		else
			$orderby="id";
		
		$query='select * from '.$table.' where '.$where.' order by '.$orderby.' '.$order.'';
		$this->query($query);
		return $result = $this->resultset();	
	}


	public function check_CConversation_id($table,$wherevalue,$wherevalue2)
	{
		$this->query("SELECT * FROM $table Where `conv_to` = $wherevalue AND `conv_from` = $wherevalue2");
		return $result = $this->resultset();
	}

	public function check_ccontractor_saved_check($table,$wherevalue,$wherevalue2)
	{
		$this->query("SELECT * FROM $table Where `contractor_id` = $wherevalue AND `job_id` = $wherevalue2");
		return $result = $this->resultset();
	}

	public function delete_all_record($table , $column , $value)
	{
		echo $query="DELETE From `".$table."` WHERE `".$column."` = '".$value."'";
		$this->query($query);
		$this->execute();
	}
	
}//end class db

?>
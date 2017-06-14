<?php 
function getPerice($Model,$key1 , $item ,$key2, $job_id) {
	$getPerice = $Model->Get_column_Double('*',$key1,$item,$key2,$job_id,PREFIX.'job_expenditure');
	return $getPerice;
}
$job_id = $_POST['job_id'];
$createdAt = time();
$jp_other_expenses = $_POST['jp_other_expenses'];
$ExpenceName = $_POST['ExpenceName'];
$DeleteValue = $this->Model->Delete_all_Records(PREFIX.'job_expenditure','job_id',$job_id);
$i=0;
foreach ($jp_other_expenses as $key => $value) {
	$data4 =  array(
		'job_id'=>$job_id, 
		'name'=>$value,
		'price'=>$ExpenceName[$i],
		'created_at'=>$createdAt,
		'modified_at'=>$createdAt
	);
	$Results3 = $this->Model->Insert_users($data4,PREFIX.'job_expenditure');
$i++;
}
$includedExpenditure = array();
      $includedExpenditureIndex = array();
      $otherExpenditure = $this->Model->Get_column1('*','job_id',$job_id,PREFIX.'job_expenditure');

      foreach ($otherExpenditure as $key => $value) {
       $includedExpenditure[] = $value['name'];
       $includedExpenditureIndex = array('name' => $value['name'] , 'id' => $value['id']);
       //echo '<input type="hidden" name="externalExpnditureArray[]" value="'. htmlspecialchars(serialize($includedExpenditureIndex)). '">';
      }

 ?>

 <label for="cFood" class="custom-checkbox">
<input disabled id="cFood"  name="extra[]" type="checkbox" <?php if (in_array("food", $includedExpenditure)) { $getDetails = getPerice($this->Model ,"name" ,"food" ,"job_id", $value['id']); ?> value="<?php echo $getDetails[0]['price']; ?>" <?php } ?> <?php if (in_array("food", $includedExpenditure)) { echo "checked"; } ?>>
<span class="custom-check"></span> Cover Food</label>
<label for="cParking" class="custom-checkbox">
<input disabled id="cParking"  name="extra[]" type="checkbox" <?php if (in_array("food", $includedExpenditure)) { $getDetails = getPerice($this->Model ,"name" ,"parking" ,"job_id", $value['id']); ?> value="<?php echo $getDetails[0]['price']; ?>" <?php } ?> <?php if (in_array("parking", $includedExpenditure)) { echo "checked"; } ?>>
<span class="custom-check"></span> Cover Parking</label>
<label for="cTolls" class="custom-checkbox">
<input disabled id="cTolls"  name="extra[]" type="checkbox"  <?php if (in_array("food", $includedExpenditure)) { $getDetails = getPerice($this->Model ,"name" ,"tolls" ,"job_id", $value['id']); ?> value="<?php echo $getDetails[0]['price']; ?>" <?php } ?>  <?php if (in_array("tolls", $includedExpenditure)) { echo "checked"; } ?>>
<span class="custom-check"></span> Cover Tolls</label>
<label for="cTips" class="custom-checkbox">
<input disabled id="cTips"  name="extra[]" type="checkbox"   <?php if (in_array("food", $includedExpenditure)) { $getDetails = getPerice($this->Model ,"name" ,"tips" ,"job_id", $value['id']); ?> value="<?php echo $getDetails[0]['price']; ?>" <?php } ?>  <?php if (in_array("tips", $includedExpenditure)) { echo "checked"; } ?>>
<span class="custom-check"></span> Cover Tips</label>
<label for="cOther" class="custom-checkbox">
<input disabled id="cOther"  name="extra[]" type="checkbox"   <?php if (in_array("food", $includedExpenditure)) { $getDetails = getPerice($this->Model ,"name" ,"other" ,"job_id", $value['id']); ?> value="<?php echo $getDetails[0]['price']; ?>" <?php } ?>  <?php if (in_array("other", $includedExpenditure)) { echo "checked"; } ?>>
<span class="custom-check"></span> Cover Other Expenses</label>
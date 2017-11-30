<script type="text/javascript">
  window.onload = function() {
    var chart = new CanvasJS.Chart("chartContainer", {
      title: {
        text: "Line Chart"
      },
      data: [{
        type: "line",
        dataPoints: [
        <?php 
        foreach ($myarrayCollectsCheck as $key => $value) {

          $dateCheck = explode("-" , $value['createdDateCheck']);
          ?>
          { x: new Date(<?php echo $dateCheck[0]; ?>, parseInt(<?php echo $dateCheck[1]; ?>) -1, <?php echo $dateCheck[2]; ?>), y: <?php echo $value['count']; ?> },
          <?php
        }
         ?>
         
        ]
      }]
    });
    chart.render();
    var chartPrice = new CanvasJS.Chart("chartContainerPrice", {
      title: {
        text: "Line Chart"
      },
      data: [{
        type: "line",
        dataPoints: [
        <?php 
        foreach ($priceActivity as $key => $value2) {
          /* echo "<pre>";
            print_r($value2);
          echo "</pre>";*/
          $dateCheck = explode("-" , $value2['createdDateCheck']);
          ?>
          { x: new Date(<?php echo $dateCheck[0]; ?>, parseInt(<?php echo $dateCheck[1]; ?>) -1, <?php echo $dateCheck[2]; ?>), y: <?php echo $value2['Price']; ?> },
          <?php
        }
         ?>
         
        ]
      }]
    });
    chartPrice.render();
    var chartContainerActivities = new CanvasJS.Chart("chartContainerActivities", {
      title: {
        text: "Line Chart"
      },
      data: [{
        type: "line",
        dataPoints: [
        <?php 
        foreach ($myarrayCollectsCheck1 as $key => $value1) {
          $dateCheck = explode("-" , $value1['createdDateCheck']);
          ?>
          { x: new Date(<?php echo $dateCheck[0]; ?>, parseInt(<?php echo $dateCheck[1]; ?>) -1, <?php echo $dateCheck[2]; ?>), y: <?php echo $value1['count']; ?> },
          <?php
        }
         ?>
         
        ]
      }]
    });
    chartContainerActivities.render();
  }
  </script>
  <main role="main">
  <section class="contracts-wrap">
    <div class="container">
      <div class="contracts-main reports-expenses-main">
        <h3>Reports</h3>
        <div class="contracts-tabs"> 
          <!-- Nav tabs -->
          <ul class="tabs-nav" role="tablist">
            <li role="presentation" class="active"><a href="#summary" aria-controls="sa" role="tab" data-toggle="tab">Summary</a></li>
            <li role="presentation"><a href="#transactions" aria-controls="aa" role="tab" data-toggle="tab">Transactions</a></li>
            <li role="presentation"><a href="#expenseReports" aria-controls="ep" role="tab" data-toggle="tab">Expense Reports</a></li>
          </ul>
          <!-- Tab panes -->
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="summary">
              <div class="summary-chart-cover">
                <ul>
                  <li>
                    <p>Number of job: <?php echo $completed_jobs; ?></p>
                    <p>Number of Activities Completed: <?php echo $comp_acti; ?></p>
                    <p>Total Earned: $0000</p>
                  </li>
                  <li> <div id="chartContainer" style="height: 242px; width: 100%;"></div> </li>
                  <li> <div id="chartContainerActivities" style="height: 242px; width: 100%;"></div> </li>
                  <li> <div id="chartContainerPrice" style="height: 242px; width: 100%;"></div> </li>
                </ul>
                <div class="summary-btn-group">
                  <button type="submit" id="past" class="btn btn-blue">Actual</button>
                  <button type="submit" id="future"  class="btn btn-gray">Projected</button>
                </div>
              </div>
              <div class="job-activities-table">
                <div class="job-activities-table-Inner">
                  <div class="text-right"> 
					<a href="javascript:void(0);" id="weekly" class="btn btn-blue">Weekly</a>
					<a href="javascript:void(0);" id="monthly"  class="btn btn-gray">Monthly</a>
				  </div>
                  <table  id="week_month_status" width="100%" border="0" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<th scope="col">Weekly Activity Sheet</th>
							<th> <?php echo $sixdaysbfr=strftime("%A",strtotime(date('Y/m/d',strtotime("-6 days"))));?></th>
							<th> <?php echo $fivedaybfr= strftime("%A",strtotime(date('Y/m/d',strtotime("-5 days"))));?></th>
							<th> <?php echo $fourdaybfr=strftime("%A",strtotime(date('Y/m/d',strtotime("-4 days"))));?></th>
							<th> <?php echo $threedaybfr= strftime("%A",strtotime(date('Y/m/d',strtotime("-3 days"))));?></th>
							<th> <?php echo  $twodaybfr=strftime("%A",strtotime(date('Y/m/d',strtotime("-2 days"))));?></th>
							<th> <?php echo $onedaynbfr=strftime("%A",strtotime(date('Y/m/d',strtotime("-1 days"))));?></th>
							<th> <?php echo $onday=strftime("%A",strtotime(date('Y/m/d')));?></th>
							<th scope="col">Amount Owned</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							if(!empty($weekly_time_sheet))
							{
								foreach($weekly_time_sheet as $weekly)
								{
									?>
									<tr>
										<td><?php echo $weekly["initial"];?></td>
										<td><?php echo $weekly[strtolower($sixdaysbfr)];?></td>
										<td><?php echo $weekly[strtolower($fivedaybfr)];?></td>
										<td><?php echo $weekly[strtolower($fourdaybfr)];?></td>
										<td><?php echo $weekly[strtolower($threedaybfr)];?></td>
										<td><?php echo $weekly[strtolower($twodaybfr)];?></td>
										<td><?php echo $weekly[strtolower($onedaynbfr)];?></td>
										<td><?php echo $weekly[strtolower($onday)];?></td>
										<td><?php echo "$". $weekly["end"];?></td>
									</tr>
									<?php
								}
							}
						?>
					</tbody>
                  </table>
                </div>
              </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="transactions">
              <div class="data-range-cover">
                <div class="select-data">
                  <select class="input date-range">
                    <option value="">Date Range</option>
                    <option value="this-week">This week</option>
                    <option value="last-week">Last week</option>
                    <option value="this-month">This month</option>
                    <option value="last-month">Last month</option>
					<?php 
						if(!empty($prev_months))
						{
							foreach($prev_months as $prev)
							{?>
								<option value="<?php echo "statement-".strtolower($prev);?>"><?php echo "Statement ".$prev; ?></option>
					<?php   }
						}
					?>
				  </select>
                </div>
                <!--<div class="action-btns">
                  <button type="submit" class="btn btn-blue dt-button buttons-pdf buttons-html5">Get PDF</button>
				  
				  
                  <button type="submit" class="btn btn-gray dt-button buttons-csv buttons-html5">Get CSV</button>
				  
				  <a class="btn btn-gray dt-button buttons-csv buttons-html5" tabindex="0" aria-controls="transac" href="#"><span>CSV</span></a>
				  
                </div>-->
              </div>
              <!--<div class="sort-by-cover">
                <ul class="pd-thr-group">
                  <li>
                    <label>Sort By</label>
                    <select class="input date-range">
                      <option>Expense Type</option>
                      <option value="">Expense Type</option>
                      <option value="salary">Salary</option> 
					  <option value="mileage">Mileage</option> 
					  <option value="allowable_overage">Allowable Overage</option>
					  <option value="other">Other expenses</option>
                    </select>
                  </li>
                  <li>
                    <label>Sort By Job</label>
                    <select class="input">
                      <option>Contractor</option>
                      <option>Older</option>
                    </select>
                  </li>
                  <li>
                    <label>Sort By Company</label>
                    <select class="input">
                      <option>Activity ID</option>
                      <option>Older</option>
                    </select>
                  </li>
                  <li>
                    <input type="submit" value="GO" class="btn btn-blue">
                  </li>
                </ul>
              </div>-->
              <div class="job-activities-table">
                <h3>Activities</h3>
                <div class="job-activities-table-Inner">
                  <table width="100%" border="0" id="transac" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<th scope="col">Date</th>
							<th scope="col">Expenses</th>
							<th scope="col">Description</th>
							<th scope="col">Job Name</th>
							<th scope="col">Company Name</th>
							<th scope="col">Amount</th>
							<th scope="col">Transaction #</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						if(!empty($transactions))
						{
							foreach($transactions as $trans)
							{
								?>
								<tr>
									<td scope="col"><?php echo $trans['date']; ?></td>
									<td scope="col"><?php echo $trans['expense']; ?></td>
									<td scope="col">Paid (<?php echo $trans['description']; ?>)</td>
									<td scope="col"><?php echo $trans['job_name']; ?></td>
									<td scope="col"><?php echo $trans['company']; ?></td>
									<td scope="col">$<?php echo $trans['amount']; ?></td>
									<td scope="col">0000</td>
								</tr>
								<?php
							}
						}
						?>
					</tbody>
                  </table>
                </div>
              </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="expenseReports">
              <div class="data-range-cover">
                <div class="select-data">
                  <select class="input date-range">
                    <option>Date Range</option>
                    <option value="this-week">This week</option>
                    <option value="last-week">Last week</option>
                    <option value="this-month">This month</option>
                    <option value="last-month">Last month</option>
					<?php 
						if(!empty($prev_months))
						{
							foreach($prev_months as $prev)
							{?>
								<option value="<?php echo "statement-".strtolower($prev);?>"><?php echo "Statement ".$prev; ?></option>
					<?php   }
						}
					?>
                  </select>
                </div>
                <!--<div class="action-btns">
                  <button type="button" id="ex-pdf" class="btn btn-blue">Get PDF</button>
                  <button type="button" id="ex-csv" class="btn btn-gray">Get CSV</button>
                </div>-->
              </div>
              <!--Last month--<div class="sort-by-cover">
                <ul>
                  <li>
                    <label>Sort By</label>
                    <select class="input">
                      <option value="">Expense Type</option>
                      <option value="salary">Salary</option> 
					  <option value="mileage">Mileage</option> 
					  <option value="allowable_overage">Allowable Overage</option>
					  <option value="other">Other expenses</option>
                    </select>
                  </li>
                  <li>
                    <label>Sort By</label>
                    <select class="input">
                      <option>Activity ID</option>
                      <option>Older</option>
                    </select>
                  </li>
                  <li>
                    <input type="submit" value="GO" class="btn btn-blue">
                  </li>
                </ul>
              </div>-->
              <div class="job-activities-table">
                <h3>Activities</h3>
                <div class="job-activities-table-Inner">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0" id="expense">
					<thead>
						<tr>
						  <th scope="col">Date</th>
						  <th scope="col">Job Name</th>
						  <th scope="col">Activity Name</th>
						  <th scope="col">Company</th>
						  <th scope="col">Amount</th>
						  <th scope="col">Activity ID</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						if(!empty($expenses))
						{
							foreach($expenses as $expense)
							{
								?>
								<tr>
									<td scope="col"><?php echo $expense['date']; ?></td>
									<td scope="col"><?php echo $expense['job_name']; ?></td>
									<td scope="col"><?php echo $expense['activity_name']; ?></td>
									<td scope="col"><?php echo $expense['company']; ?></td>
									<td scope="col">$<?php echo $expense['amount']; ?></td>
									<td scope="col"><?php echo $expense['activity_id']; ?></td>
								</tr><?php
							}
						}
						?>
					</tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
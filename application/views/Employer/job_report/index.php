<nav class="sub-navigation">
  <div class="container">
    <ul>
      <li><a href="#">My jobs</a></li>
      <li><a href="#">Contracts</a></li>
      <li><a href="#">Post a job</a></li>
    </ul>
  </div>
</nav>
<main role="main">
  <section class="contracts-wrap">
    <div class="container">
      <div class="contracts-main">
        <h3>My Job Reports</h3>
        <div class="contracts-tabs"> 
          <!-- Nav tabs -->
          <ul class="tabs-nav" role="tablist">
            <li role="presentation" class="active"><a href="#active" aria-controls="hired" role="tab" data-toggle="tab">Active Jobs</a></li>
            <li role="presentation"><a href="#closed" aria-controls="past-hired" role="tab" data-toggle="tab">Closed Jobs</a></li>
          </ul>
          <!--- Sorting buttons --->
          <div class="sorting-btns clearfix"><a class="btn btn-blue" href="#">Sort 1</a><a class="btn btn-blue" href="#">Sort 2</a><a class="btn btn-blue" href="#">Sort 3</a></div>
          <!-- Tab panes -->
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="active">
              <div class="job-activities-table"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <th scope="col">Contractor</th>
                  <th scope="col">Job Name</th>
                  <th scope="col">Activity Name</th>
                  <th scope="col">Activity Time</th>
                  <th scope="col">Location</th>
                  <th scope="col">Contact Name</th>
                  <th scope="col">Job Report</th>
                  <th scope="col">Status</th>
                </tr>
                <?php foreach ($jobs as $key => $value): ?>
             <?php 
                    echo "<pre>";
                      print_r($value);
                    echo "</pre>";
                 ?>
                <tr>
                  <td>David Dee</td>
                  <td><a href="#">Product launch</a></td>
                  <td>Minneapolis Show</td>
                  <td><span class="ac-date">11/30/16</span> <span class="ac-time">6:00 PM</span></td>
                  <td>Minneapolis</td>
                  <td>Drew Connor</td>
                  <td><a href="#">N/A</a></td>
                  <td>Pending</td>
                </tr>
                <?php endforeach ?>
              </table></div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="closed">
            <div class="job-activities-table"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <th scope="col">Contractor</th>
                  <th scope="col">Job Name</th>
                  <th scope="col">Activity Name</th>
                  <th scope="col">Date</th>
                  <th scope="col">Location</th>
                  <th scope="col">Contact Name</th>
                  <th scope="col">Job Report</th>
                  <th scope="col">Feedback</th>
                  <th scope="col">Paid</th>
                </tr>
                <tr>
                  <td>David Dee</td>
                  <td><a href="#">Product launch</a></td>
                  <td>Minneapolis Show</td>
                  <td><span class="ac-date">11/30/16</span> <span class="ac-time">6:00 PM</span></td>
                  <td>Minneapolis</td>
                  <td>Drew Connor</td>
                  <td><a href="#">View</a></td>
                  <td>Show stars</td>
                  <td>$200</td>
                </tr>
                <tr>
                  <td>ForceFlexing</td>
                  <td><a href="#">Product launch</a></td>
                  <td>Minneapolis Show</td>
                  <td><span class="ac-date">11/30/16</span> <span class="ac-time">6:00 PM</span></td>
                  <td>Minneapolis</td>
                  <td>Drew Connor</td>
                  <td><a href="#">View</a></td>
                  <td>Show stars</td>
                  <td>$200</td>
                </tr>
                <tr>
                  <td>ForceFlexing</td>
                  <td><a href="#">Product launch</a></td>
                  <td>Minneapolis Show</td>
                  <td><span class="ac-date">11/30/16</span> <span class="ac-time">6:00 PM</span></td>
                  <td>Minneapolis</td>
                  <td>Drew Connor</td>
                  <td><a href="#">View</a></td>
                  <td>Show stars</td>
                  <td>$200</td>
                </tr>
                <tr>
                  <td>ForceFlexing</td>
                  <td><a href="#">Product launch</a></td>
                  <td>Minneapolis Show</td>
                  <td><span class="ac-date">11/30/16</span> <span class="ac-time">6:00 PM</span></td>
                  <td>Minneapolis</td>
                  <td>Drew Connor</td>
                  <td><a href="#">View</a></td>
                  <td>Show stars</td>
                  <td>$200</td>
                </tr>
              </table></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
<main role="main">
  <section class="innerBanner" style="background-image:url(<?php if(isset($data) && $data['banner_image'] != "" ){	echo BASE_URL.'/static/uploads/pages/'.$data['banner_image'];	} ?>);">
      <div class="container">
        <div class="bannerCaption <?php if(isset($data) && $data['tag_line_float'] != "" ){	echo $data['tag_line_float'];	} ?> ">
          <h2><?php echo $data['tag_line']; ?></h2>
        </div>
      </div>
   
  </section>
 <section class="innerWrap">
 <div class="container">
 <aside class="pageSidebar">
 <div class="brandLogo">
 <img src="<?php  echo BASE_URL.'/static/';?>images/logo-graphic.png" alt="logo"/>
 </div>
 </aside>
 <aside class="mainPage">
 <h1><?php echo $data['title']; ?></h1>
 <?php echo $data['content']; ?>
 </aside>
 </div>
 </section>
</main>
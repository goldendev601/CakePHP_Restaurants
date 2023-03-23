<div class="content-wrapper">
   <section class="content-header">
      <h1>
         Dashboard
      </h1>
      <ol class="breadcrumb">
         <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
         <li class="active">Dashboard</li>
      </ol>
   </section>
   <section class="content">
      <div class="row">
         <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
               <span class="info-box-icon bg-aqua"><i class="ion ion-ios-gear-outline"></i></span>
               <div class="info-box-content">
                  <span class="info-box-text">Sales</span>
                  <span class="info-box-number"><?= ($siteSettings['site_currency']);?><?php echo number_format($salesPrice,2);?></span>
               </div>
            </div>
         </div>
         <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
               <span class="info-box-icon bg-red"><i class="fa fa-google-plus"></i></span>
               <div class="info-box-content">
                  <span class="info-box-text">Delivered</span>
                  <span class="info-box-number"><?= $deliveredCount;?></span>
               </div>
            </div>
         </div>
         <div class="clearfix visible-sm-block"></div>
         <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
               <span class="info-box-icon bg-green"><i class="ion ion-ios-cart-outline"></i></span>
               <div class="info-box-content">
                  <span class="info-box-text">No Orders</span>
                  <span class="info-box-number"><?= $no_orders;?></span>
               </div>
            </div>
         </div>
         <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
               <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>
               <div class="info-box-content">
                  <span class="info-box-text">No Customers</span>
                  <span class="info-box-number"><?= $totalUsers;?></span>
               </div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-md-6">
            <div class="box box-info box-studio">
               <div class="box-header with-border">
                  <h3 class="box-title">All Orders</h3>
               </div> 
               <div class="col-xs-12"> 
	                <div class="inner">
						<h3><?= $no_orders;?></h3>
						<p>New Orders</p>
					</div>   
			   </div>  			    
               <div class="col-xs-12"> 
	                <div class="inner">
						<h3><?= ($siteSettings['site_currency']);?><?php echo number_format($salesPrice,2);?></h3>
						<p>Orders Price</p>
					</div>   
			   </div>        
            </div>
         </div>
         <div class="col-md-6">
            <div class="box box-success box-studio">
               <div class="box-header with-border">
                  <h3 class="box-title">User Details</h3>
               </div>
               <div class="col-xs-6 col-sm-6"> 
	                <div class="inner">
						<h3><?= $totalUsers;?></h3>
						<p>Total Users</p>
					</div>   
			   </div>  			    
               <div class="col-xs-6 col-sm-6"> 
	                <div class="inner">
						<h3><?= $deactiveUsers;?></h3>
						<p>Deactive Users</p>
					</div>   
			   </div>
               <div class="col-xs-6 col-sm-6"> 
	                <div class="inner">
						<h3><?= $activeUsers;?></h3>
						<p>Active Users</p>
					</div>   
			   </div>  			    

            </div>
         </div>
         <div class="col-md-6">
            <div class="box box-warning box-studio">
               <div class="box-header with-border">
                  <h3 class="box-title">Restaurant Details</h3>
               </div>                 
               <div class="col-xs-6 col-sm-6"> 
	                <div class="inner">
						<h3><?= $totalRestaurants;?></h3>
						<p>Total Restaurants</p>
					</div>   
			   </div>  			    
               <div class="col-xs-6 col-sm-6"> 
	                <div class="inner">
						<h3><?= $deactiveRestaurants;?></h3>
						<p>Deactive Restaurants</p>
					</div>   
			   </div>
               <div class="col-xs-6 col-sm-6"> 
	                <div class="inner">
						<h3><?= $activeRestaurants;?></h3>
						<p>Active Restaurants</p>
					</div>   
			   </div>  			    

            </div>
         </div>
         <div class="col-md-6">
            <div class="box box-danger box-studio">
               <div class="box-header with-border">
                  <h3 class="box-title">Driver Details</h3>
               </div>
               <div class="col-xs-6 col-sm-6"> 
	                <div class="inner">
						<h3><?= $totalDrivers;?></h3>
						<p>Total Drivers</p>
					</div>   
			   </div>  			    
               <div class="col-xs-6 col-sm-6"> 
	                <div class="inner">
						<h3><?= $deactiveDrivers;?></h3>
						<p>Deactive Drivers</p>
					</div>   
			   </div>
               <div class="col-xs-6 col-sm-6"> 
	                <div class="inner">
						<h3><?= $activeDrivers;?></h3>
						<p>Active Drivers</p>
					</div>   
			   </div>  			    

            </div>
         </div>
      </div>
   </section>
</div>


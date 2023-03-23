<div class="form-group clearfix">
	<div class="form-group clearfix">
		<label for="guestCount" class="col-sm-4 control-label name">
				Book Table id : </label>
		<div class="col-sm-7 padding-t-7"> <?php
			echo $bookDetails['booking_id']; ?>
		</div>
	</div>

	<div class="form-group clearfix">
		<label for="guestCount" class="col-sm-4 control-label name">
				Guest Count : </label>
		<div class="col-sm-7 padding-t-7"> <?php
			echo $bookDetails['guest_count']; ?>
		</div>
	</div>


	<div class="form-group clearfix">
		<label for="guestCount" class="col-sm-4 control-label name">
				Customer Name : </label>
		<div class="col-sm-7 padding-t-7"> <?php
			echo $bookDetails['customer_name']; ?>
		</div>
	</div>


	<div class="form-group clearfix">
		<label for="guestCount" class="col-sm-4 control-label name">
				Customer Email : </label>
		<div class="col-sm-7 padding-t-7"> <?php
			echo $bookDetails['booking_email']; ?>
		</div>
	</div>

	<div class="form-group clearfix">
		<label for="guestCount" class="col-sm-4 control-label name">
				Customer Phone : </label>
		<div class="col-sm-7 padding-t-7"> <?php
			echo $bookDetails['booking_phone']; ?>
		</div>
	</div>

	<div class="form-group clearfix">
		<label for="guestCount" class="col-sm-4 control-label name">
				Booking Date : </label>
		<div class="col-sm-7 padding-t-7"> <?php
			echo $bookDetails['booking_date']; ?>
		</div>
	</div>

	<div class="form-group clearfix">
		<label for="guestCount" class="col-sm-4 control-label name">
				Booking Time : </label>
		<div class="col-sm-7 padding-t-7"> <?php
			echo $bookDetails['booking_time']; ?>
		</div>
	</div>


	<div class="form-group clearfix">
		<label for="guestCount" class="col-sm-4 control-label name">
				Status : </label>
		<div class="col-sm-7 padding-t-7"> <?php
			echo $bookDetails['status']; ?>
		</div>
	</div> <?php
	if ($bookDetails['status'] == 'Cancel') { ?>
		<div class="form-group clearfix">
			<label for="guestCount" class="col-sm-4 control-label name">
				Cancel reason : </label>
			<div class="col-sm-7 padding-t-7"> <?php
				echo $bookDetails['cancel_reason']; ?>
			</div>
		</div> <?php
	}
	
	if ($bookDetails['booking_instruction'] != '') { ?>

		<div class="form-group clearfix">
			<label for="guestCount" class="col-sm-4 control-label name">
				Instructions :</label>
			<div class="col-sm-7 padding-t-7"> <?php
				echo $bookDetails['booking_instruction']; ?>
			</div>
		</div> <?php
	}else{ ?>
		<div class="form-group clearfix">
			<label for="guestCount" class="col-sm-4 control-label name">
				Instructions :</label>
			<div class="col-sm-7 padding-t-7">----No Instructions----</div> 
		</div> <?php
	} ?>
</div>
<?php die();?>
<?php
$lpl	=	'';
$dl		=	'required=""';
?>
<div class="card mg-b-10">
  <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
      <h6 class="card-title tx-uppercase tx-12 mg-b-0"><?php echo $til;?></h6>
    </div>
  <div class="card-body">
    <form action="" method="post" class="validatform formssample" id="course" novalidate="" enctype="multipart/form-data">
      <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"> 
          <div class="form-group">
            <label>Specialization Name <span class="text-danger">*</span></label>
            <input name="specialization_name" type="text" class="form-control specialization_name" placeholder="Enter Specialization Name" required="" autocomplete="off" value="<?php echo (is_array($view) && count($view) > 0)?$view["specialization_name"]:set_value("specialization_name");?>"/>
            <?php echo form_error('specialization_name');?> 
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
          <div class="form-actions form-group">
            <button type="submit" class="btn btn-sm btn-success" name="submit" value="submit"> Save</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
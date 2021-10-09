<div class="row">
    <div class="col-lg-12">
        <?php $this->load->view("success_error");?> 
    </div>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="" method="post" class="validatform formssample" id="course" novalidate="" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                            <div class="form-group">
                                <label>Blog Title <span class="required text-danger">*</span></label>
                                <input name="blog_title" type="text" class="form-control" placeholder="Blog Title" required="" autocomplete="off" value="<?php echo is_array($view)?$view["blog_title"]: set_value("blog_title");?>"/>
                                <?php echo form_error('blog_title');?> 
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                            <div class="form-group">
                                <label>Select Module<span class="required text-danger">*</span></label>
                                <select class="form-control" name="module" required>
                                    <option value="">Select Module</option>
                                    <?php if(is_array($module) && count($module) > 0){
  foreach($module as $m){ ?>
                           <option value="<?php echo $m['sub_module_id'];?>" <?php echo (is_array($view) && $m['moduleid']  == $view["blog_module_id"])?"selected=selected":set_select("module",$m["sub_module_id"]);?>><?php echo $m['module_name']." - ".$m["sub_module_name"];?></option>
                           <?php }
}?>
                                </select>
                                <?php echo form_error('module');?> 
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                            <div class="form-group">
                                <label>Blog Description <span class="required text-danger">*</span> </label>
                                <textarea name="blog_description" type="text" class="form-control" placeholder="Blog Description"  autocomplete="off" required><?php echo is_array($view)?$view["blog_description"]:set_value("blog_description");?></textarea>
                                <?php echo form_error('blog_description');?> 
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                            <div class="form-group">
                                <label>Blog Images </label>
                                <input type="file" name="blog_image[]" type="text" class="form-control"  multiple=""/>
                                <?php echo form_error('blog_image');?> 
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                            <div class="form-group">
                                <label>Videos </label>
                                <button class="btn btn-danger float-right" onclick="add_video(event);" id="video_number" data-val=2>+ Video</button>
                                <br>
                            </div>
                                <div id="videoss">
                                    <div class="row" id="total1">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <select onchange="content_change(1)" class="form-control option1" name="video_type[]" >
                                                    <option value="">Select Video Type</option>
                                                    <?php foreach($video_type as $v){ ?>
                                                        <option value="<?php echo $v['video_type_id'];?>" fileva="<?php echo $v["video_type_text"];?>"><?php echo $v['video_type_name'];?></option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="form-group" id="content1">
                                        </div>
                                        </div>
                                        <div class="col-md-2">
                                          
                                        </div>
                                    </div>
                                <?php echo form_error('blog_image');?> 
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                            <div class="form-group">
                                <label>Seo Meta Keywords</label>
                                <input name="keywords" type="text" class="form-control"  placeholder="Enter SEO meta Keywords"  autocomplete="off" value="<?php echo (is_array($view) && count($view) > 0)?$view["blog_seo_keywords"]:set_value("blog_seo_keywords");?>"/>
                                <?php echo form_error('keywords');?> 
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                            <div class="form-group">
                                <label>Seo Meta Description</label>
                                <textarea name="seo_description" class="form-control"  placeholder="Enter SEO meta description"  autocomplete="off" ><?php echo (is_array($view) && count($view) > 0)?$view["blog_seo_description"]:set_value("blog_seo_description");?></textarea>
                                <?php echo form_error('seo_description');?> 
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
            </div><!--end card-body-->
        </div><!--end card-->
    </div>
</div> 
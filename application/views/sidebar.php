<div class="br-sideleft sideleft-scrollbar">
   <label class="sidebar-label pd-x-10 mg-t-20 op-3"><?php echo sitedata("site_name");?></label>
   <ul class="br-sideleft-menu">
      <li class="br-menu-item">
         <a href="<?php echo adminurl("Dashboard");?>" class="br-menu-link Dashboard">
            <i class="menu-item-icon icon ion-ios-home-outline tx-24"></i>
            <span class="menu-item-label">Dashboard</span>
         </a>
      </li>
      <?php if($this->session->userdata("manage-permission") == "1" || $this->session->userdata("manage-roles") == "1"){ ?>
      <li class="br-menu-item">
         <a href="javascript:void(0);" class="br-menu-link with-sub Roles Permissions">
            <i class="menu-item-icon icon ion-ios-photos-outline tx-20"></i>
            <span class="menu-item-label">Administration</span>
         </a><!-- br-menu-link -->
         <ul class="br-menu-sub spPermissions spRoles">
             <?php if($this->session->userdata("manage-permission") == "1") { ?>
             <li class="sub-item"><a href="<?php echo adminurl("Permissions");?>" class="Permissions sub-link">Permissions</a></li>
             <?php } if($this->session->userdata("manage-roles") == "1"){ ?>
             <li class="sub-item "><a href="<?php echo adminurl("Roles");?>" class="Roles sub-link">Roles</a></li>
             <?php } ?>
         </ul>
      </li>
      <?php } if($this->session->userdata("manage-video-type") == "1" || $this->session->userdata("manage-wheel-wellness") == "1"){ ?>
      <li class="br-menu-item">
         <a href="javascript:void(0);" class="br-menu-link with-sub Video-Type States Update-Video-Type Create-Video-Type Wellness Update-Wellness">
            <i class="menu-item-icon icon ion-ios-photos-outline tx-20"></i>
            <span class="menu-item-label">Masters</span>
         </a><!-- br-menu-link -->
         <ul class="br-menu-sub spVideo-Type spStates spUpdate-Video-Type spCreate-Video-Type spWellness spUpdate-Wellness">
             <?php if($this->session->userdata("manage-video-type") == "1") { ?>
             <li class="sub-item"><a href="<?php echo adminurl("Video-Type");?>" class="Video-Type Update-Video-Type Create-Video-Type sub-link">Video Type</a></li>
             <?php } if($this->session->userdata("manage-wheel-wellness") == "1") { ?>
             <li class="sub-item"><a href="<?php echo adminurl("Wellness");?>" class="Wellness Update-Wellness sub-link">Wheel of Wellness</a></li>
             <?php } ?>
         </ul>
      </li>
      <?php } if($this->session->userdata("manage-widgets") == "1" || $this->session->userdata("manage-content-pages") == "1"){ ?>
        <li class="br-menu-item">
            <a href="javascript:void(0);" class="br-menu-link with-sub Content-Pages Widgets Create-Content-Pages">
               <i class="menu-item-icon icon ion-bookmark tx-20"></i>
               <span class="menu-item-label">Content Management</span>
            </a><!-- br-menu-link -->
            <ul class="br-menu-sub spContent-Pages spWidgets spCreate-Content-Pages">
                <?php if($this->session->userdata("manage-widgets") == "1") { ?>
                <li class="sub-item"><a href="<?php echo adminurl("Widgets");?>" class="Widgets sub-link">Widgets</a></li>
                <?php } if($this->session->userdata("manage-content-pages") == "1"){ ?>
                <li class="sub-item "><a href="<?php echo adminurl("Content-Pages");?>" class="Content-Pages Create-Content-Pages sub-link">Pages</a></li>
                <?php } ?>
            </ul>
        </li>
      <?php }  if($this->session->userdata("manage-modules") == "1" || $this->session->userdata("manage-sub-module") == "1") { ?>
      <li class="br-menu-item">
         <a href="javascript:void(0);" class="br-menu-link with-sub  Update-Sub-Module Update-Module Modules Create-Sub-Module Sub-Module">
            <i class="menu-item-icon icon ion-ios-photos-outline tx-20"></i>
            <span class="menu-item-label">Modules</span>
         </a>
         <ul class="br-menu-sub spUpdate-Sub-Module spSub-Module spUpdate-Module spModules spCreate-Sub-Module">
             <?php if($this->session->userdata("manage-modules") == "1") { ?>
             <li class="sub-item"><a href="<?php echo adminurl("Modules");?>" class="Update-Module Modules sub-link">Modules</a></li>
             <?php } if($this->session->userdata("manage-sub-module") == "1"){ ?>
             <li class="sub-item "><a href="<?php echo adminurl("Sub-Module");?>" class="Sub-Module Create-Sub-Module Update-Sub-Module sub-link">Sub Modules</a></li>
             <?php } ?>
         </ul>
      </li>
      <?php } if($this->session->userdata("manage-category") == "1" || $this->session->userdata("manage-sub-category") == "1") { ?>
      <li class="br-menu-item">
         <a href="javascript:void(0);" class="br-menu-link with-sub Update-Sub-Category Create-Sub-Category Sub-Category Update-Category Category Create-Category">
            <i class="menu-item-icon icon ion-ios-photos-outline tx-20"></i>
            <span class="menu-item-label">Categories</span>
         </a>
         <ul class="br-menu-sub spSub-Category spUpdate-Sub-Category spCreate-Sub-Category spUpdate-Category spCreate-Category spCategory">
             <?php if($this->session->userdata("manage-category") == "1") { ?>
             <li class="sub-item"><a href="<?php echo adminurl("Category");?>" class="Update-Category Create-Category Category sub-link">Category</a></li>
             <?php } if($this->session->userdata("manage-sub-category") == "1"){ ?>
             <li class="sub-item "><a href="<?php echo adminurl("Sub-Category");?>" class="Create-Sub-Category Sub-Category Update-Sub-Category sub-link">Sub Categories</a></li>
             <?php } ?>
         </ul>
      </li>
      <?php }  if($this->session->userdata("manage-specialization") == "1") { ?>
      <li class="br-menu-item">
            <a href="<?php echo adminurl("Specialization");?>" class="br-menu-link Specialization">
                <i class="menu-item-icon icon ion-disc tx-24"></i>
                <span class="menu-item-label">Specialization</span>
            </a>
      </li>
      <?php }  if($this->session->userdata("manage-vendors") == "1" || $this->session->userdata("manage-sub-vendors") == "1"){ ?>
      <li class="br-menu-item">
         <a href="javascript:void(0);" class="br-menu-link Create-Sub-Vendor with-sub Vendors Sub-Vendors Create-Vendor Update-Sub-Vendor Update-Vendor">
            <i class="menu-item-icon icon ion-ios-photos-outline tx-20"></i>
            <span class="menu-item-label">Vendors</span>
         </a>
         <ul class="br-menu-sub spVendors spUpdate-Vendor spSub-Vendors spUpdate-Sub-Vendor spCreate-Sub-Vendor spCreate-Vendor">
             <?php if($this->session->userdata("manage-vendors") == "1") { ?>
             <li class="sub-item"><a href="<?php echo adminurl("Vendors");?>" class="Vendors Create-Vendor sub-link Update-Vendor">Vendors</a></li>
             <?php } if($this->session->userdata("manage-sub-vendors") == "1"){ ?>
             <li class="sub-item "><a href="<?php echo adminurl("Sub-Vendors");?>" class="Update-Sub-Vendor Create-Sub-Vendor Sub-Vendors sub-link">Sub Vendors</a></li>
             <?php } ?>
         </ul>
      </li>
      <?php }  if($this->session->userdata("manage-homecare") == "1"){ ?>
      <li class="br-menu-item">
         <a href="javascript:void(0);" class="br-menu-link with-sub Homecare-Packages Homecare-Tests Create-Works Updte-Works Works Update-Home-Tests Update-Home-Packages">
            <i class="menu-item-icon icon ion-ios-photos-outline tx-20"></i>
            <span class="menu-item-label">Home Care</span>
         </a>
         <ul class="br-menu-sub spHomecare-Packages spUpdate-Home-Packages spWorks spUpdate-Works spCreate-Works spHomecare-Tests spUpdate-Home-Tests">
             <?php if($this->session->userdata("manage-homecare-packages") == "1") { ?>
             <li class="sub-item"><a href="<?php echo adminurl("Homecare-Packages");?>" class="Homecare-Packages Create-Works Updte-Works Works Update-Home-Packages sub-link">Packages</a></li>
             <?php } if($this->session->userdata("manage-homecare-tests") == "1"){ ?>
             <li class="sub-item "><a href="<?php echo adminurl("Homecare-Tests");?>" class="Update-Home-Tests Homecare-Tests sub-link">Tests</a></li>
             <?php } ?>
         </ul>
      </li>
      <?php } if($this->session->userdata("manage-package") == "1" || $this->session->userdata("manage-package-details") == "1"){ ?>
        <li class="br-menu-item">
            <a href="javascript:void(0);" class="br-menu-link with-sub Update-Package-Details Package-Details Package Update-Package">
               <i class="menu-item-icon icon ion-bookmark tx-18"></i>
               <span class="menu-item-label">Packages</span>
            </a>
            <ul class="br-menu-sub spPackage spUpdate-Package spUpdate-Package-Details spPackage-Details ">
                <?php if($this->session->userdata("manage-package") == "1") { ?>
                <li class="sub-item"><a href="<?php echo adminurl("Package");?>" class="Widgets sub-link">Packages</a></li>
                <?php } if($this->session->userdata("manage-package-details") == "1"){ ?>
                <li class="sub-item "><a href="<?php echo adminurl("Package-Details");?>" class="Update-Package-Details Package-Details sub-link">Package Details</a></li>
                <?php } ?>
            </ul>
        </li>
      <?php }   if($this->session->userdata("manage-blogs") == "1") { ?>
        <li class="br-menu-item">
            <a href="<?php echo adminurl("Blogs");?>" class="br-menu-link Blogs Update-Blog Create-Blog">
                <i class="menu-item-icon icon ion-ios-bookmarks-outline tx-24"></i>
                <span class="menu-item-label">Blogs</span>
            </a><!-- br-menu-link -->
        </li>
      <?php }  if($this->session->userdata("manage-question-answers") == "1") { ?>
      <li class="br-menu-item">
            <a href="<?php echo adminurl("Question-Answers");?>" class="br-menu-link Question-Answers Create-Question-Answer Update-Question-Answer">
                <i class="menu-item-icon icon ion-ios-help-outline tx-24"></i>
                <span class="menu-item-label">Question & Answers</span>
            </a>
      </li>
      <?php } if($this->session->userdata("manage-app-users") == "1") { ?>
       <li class="br-menu-item">
         <a href="javascript:void(0);" class="br-menu-link with-sub App-Vendors App-Customers">
            <i class="menu-item-icon icon ion-ios-photos-outline tx-20"></i>
            <span class="menu-item-label">APP Users</span>
         </a>
         <ul class="br-menu-sub sp">
             <li class="sub-item"><a href="<?php echo adminurl("App-Vendors");?>" class="App-Vendors sub-link">Vendors</a></li>
             <li class="sub-item"><a href="<?php echo adminurl("App-Customers");?>" class="App-Customers sub-link">Customers</a></li>
         </ul>
      </li>
      <?php } if($this->session->userdata("manage-hospitals") == "1") { ?>
       <li class="br-menu-item">
         <a href="javascript:void(0);" class="br-menu-link with-sub App-Vendors App-Customers">
            <i class="menu-item-icon icon ion-ios-photos-outline tx-20"></i>
            <span class="menu-item-label">Hospitals</span>
         </a>
         <ul class="br-menu-sub sp">
             <li class="sub-item"><a href="<?php echo adminurl("Specialities");?>" class="Specialities sub-link">Specialities</a></li>
             <li class="sub-item"><a href="<?php echo adminurl("Packages");?>" class="Packages sub-link">Packages</a></li>
             <li class="sub-item"><a href="<?php echo adminurl("Facilities");?>" class="Facilities sub-link">Facilities</a></li>
         </ul>
      </li>
      <?php } if($this->session->userdata("manage-health-category") == "1" || $this->session->userdata("manage-health-sub-category") == "1") { ?>
      <li class="br-menu-item">
         <a href="javascript:void(0);" class="br-menu-link with-sub Update-Health-Sub-Category Create-Health-Sub-Category Health-Sub-Category Health-Update-Category Health-Category Create-Health-Category">
            <i class="menu-item-icon icon ion-ios-photos-outline tx-20"></i>
            <span class="menu-item-label">Health Categories</span>
         </a>
         <ul class="br-menu-sub spHealth-Sub-Category spUpdate-Sub-Health-Category spUpdate-Health-Category spHealth-Category">
             <?php if($this->session->userdata("manage-health-category") == "1") { ?>
             <li class="sub-item"><a href="<?php echo adminurl("Health-Category");?>" class="Update-Health-Category Health-Category sub-link">Health Category</a></li>
             <?php } if($this->session->userdata("manage-health-sub-category") == "1"){ ?>
             <li class="sub-item "><a href="<?php echo adminurl("Health-Sub-Category");?>" class="Health-Sub-Category Update-Sub-Health-Category sub-link">Sub Health Categories</a></li>
             <?php } ?>
         </ul>
      </li>
      <?php } if($this->session->userdata("chat-room-configuration") == "1") { ?>
      <li class="br-menu-item">
            <a href="<?php echo adminurl("Chat-Room-Configuration");?>" class="br-menu-link Update-bot Chat-Room-Configuration">
                <i class="menu-item-icon icon ion-disc tx-24"></i>
                <span class="menu-item-label">Auto Chat Bot</span>
            </a>
      </li>
      <?php }  if($this->session->userdata("manage-symptoms-chat-bot") == "1") { ?>
      <li class="br-menu-item">
            <a href="<?php echo adminurl("Symptoms-Chat-Bot");?>" class="br-menu-link Symptoms-Chat-Bot">
                <i class="menu-item-icon icon ion-disc tx-24"></i>
                <span class="menu-item-label">Symptoms Chat Bot</span>
            </a>
      </li>
      <?php }  if($this->session->userdata("manage-consult-chat-bot") == "1") { ?>
      <li class="br-menu-item">
            <a href="<?php echo adminurl("Consult-Chat-Bot");?>" class="br-menu-link Consult-Chat-Bot">
                <i class="menu-item-icon icon ion-disc tx-24"></i>
                <span class="menu-item-label">Consult Chat Bot</span>
            </a>
      </li>
      <?php }  if($this->session->userdata("manage-health-tips") == "1") { ?>
      <li class="br-menu-item">
            <a href="<?php echo adminurl("Health-Tips");?>" class="br-menu-link Health-Tips">
                <i class="menu-item-icon icon ion-ios-help-outline tx-24"></i>
                <span class="menu-item-label">Health Tips</span>
            </a>
      </li>
      <?php }  ?>
   </ul>
</div>
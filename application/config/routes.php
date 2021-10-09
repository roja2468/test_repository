<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['Sujeevan-Admin/Permissions']   =   'permissions/index';  
$route['Sujeevan-Admin/AjaxPermission']     =   'permissions/AjaxPermission';  
$route['Sujeevan-Admin/Dashboard']     =   'dashboard/index';  
$route['Sujeevan-Admin/Logout']        =   'login/logout';  

$route['Sujeevan-Admin/Ajax-Role-Check']        =   'role/unique_role_name';  
$route['Sujeevan-Admin/AjaxRoleCheck']          =   'role/uniquerolename'; 
$route['Sujeevan-Admin/Ajax-Role-Active']       =   'role/activedeactive';  
$route['Sujeevan-Admin/Roles']                  =   'role/index';  
$route['Sujeevan-Admin/viewRole/(:num)']        =   'role/viewRole/$1';  
$route['Sujeevan-Admin/Update-Role/(:any)']     =   'role/update_role/$1';  
$route['Sujeevan-Admin/Delete-Role/(:any)']     =   'role/delete_role/$1';  

$route['Sujeevan-Admin/Widgets']                =   'widgets/index'; 
$route['Sujeevan-Admin/viewWidget/(:num)']      =   'widgets/viewWidget/$1'; 
$route['Sujeevan-Admin/Update-Widget/(:any)']   =   'widgets/update_widget/$1'; 
$route['Sujeevan-Admin/Delete-Widget/(:any)']   =   'widgets/delete_widget/$1'; 
$route['Sujeevan-Admin/Ajax-Widget-Check']      =   'widgets/unique_widget_name';

$route['Sujeevan-Admin/Modules']                  =   'allmodules/index';
$route['Sujeevan-Admin/Ajax-Module-Active']       =   'allmodules/activedeactive';
$route['Sujeevan-Admin/viewModules/(:any)']       =   'allmodules/viewModules/$1';
$route['Sujeevan-Admin/Update-Module/(:any)']     =   'allmodules/update_module/$1';



$route['Sujeevan-Admin/Sub-Module']                  =   'allmodules/submodules/index'; 
$route['Sujeevan-Admin/viewSubModule/(:num)']        =   'allmodules/submodules/viewSubModule/$1'; 
$route['Sujeevan-Admin/Create-Sub-Module']           =   'allmodules/submodules/create_sub_module'; 
$route['Sujeevan-Admin/Update-Sub-Module/(:any)']    =   'allmodules/submodules/update_sub_module/$1'; 
$route['Sujeevan-Admin/Delete-Sub-Module/(:any)']    =   'allmodules/submodules/delete_sub_module/$1'; 
$route['Sujeevan-Admin/Ajax-Sub-Module-Active']      =   'allmodules/submodules/activedeactive';
$route['Sujeevan-Admin/Ajax-Submodule-Check']        =   'allmodules/submodules/unique_submodule_name'; 


$route['Sujeevan-Admin/Ajax-Content-Page-Active']   =   'content_pages/activedeactive';
$route['Sujeevan-Admin/Content-Pages']              =   'content_pages/index'; 
$route['Sujeevan-Admin/Create-Content-Pages']       =   'content_pages/create_content'; 
$route['Sujeevan-Admin/viewContent/(:num)']         =   'content_pages/viewContent/$1'; 
$route['Sujeevan-Admin/Update-Content-Page/(:any)'] =   'content_pages/update_content_page/$1'; 
$route['Sujeevan-Admin/Delete-Content-Page/(:any)'] =   'content_pages/delete_content_page/$1';

$route['Sujeevan-Admin/Blogs']                  =   'blogs/index'; 
$route['Sujeevan-Admin/viewBlog/(:num)']        =   'blogs/viewBlog/$1'; 
$route['Sujeevan-Admin/Create-Blog']            =   'blogs/create_blog'; 
$route['Sujeevan-Admin/Update-Blog/(:any)']     =   'blogs/update_blog/$1'; 
$route['Sujeevan-Admin/Delete-Blog/(:any)']     =   'blogs/delete_blog/$1'; 
$route['Sujeevan-Admin/Ajax-Blog-Active']       =   'blogs/activedeactive';
$route['Sujeevan-Admin/Ajax-Blog-Options']      =   'blogs/ajax_options';


$route['Sujeevan-Admin/Video-Type']                  =   'video_type/index'; 
$route['Sujeevan-Admin/viewVideoType/(:num)']        =   'video_type/viewvideo_type/$1'; 
$route['Sujeevan-Admin/Create-Video-Type']           =   'video_type/create_video_type'; 
$route['Sujeevan-Admin/Update-Video-Type/(:any)']    =   'video_type/update_video_type/$1'; 
$route['Sujeevan-Admin/Delete-Video-Type/(:any)']    =   'video_type/delete_video_type/$1'; 
$route['Sujeevan-Admin/Ajax-Video-Type-Active']      =   'video_type/activedeactive';


$route['Sujeevan-Admin/Question-Answers']                 =   'questions/index'; 
$route['Sujeevan-Admin/viewQa/(:num)']                    =   'questions/viewqa/$1'; 
$route['Sujeevan-Admin/Create-Question-Answer']           =   'questions/create_qa'; 
$route['Sujeevan-Admin/Update-Question-Answer/(:any)']    =   'questions/update_qa/$1'; 
$route['Sujeevan-Admin/Delete-Question-Answer/(:any)']    =   'questions/delete_qa/$1'; 
$route['Sujeevan-Admin/Ajax-Question-Answer-Active']      =   'questions/activedeactive';

/** Health Category ****/
$route['Sujeevan-Admin/Ajax-Category-Check']              =   'health_category/unique_category_name'; 
$route['Sujeevan-Admin/Health-Category']                  =   'health_category/index'; 
$route['Sujeevan-Admin/viewCategoryHealth/(:num)']        =   'health_category/viewCategoryHealth/$1'; 
$route['Sujeevan-Admin/Update-Health-Category/(:any)']    =   'health_category/update_category/$1'; 
$route['Sujeevan-Admin/Delete-Health-Category/(:any)']    =   'health_category/delete_category/$1'; 
$route['Sujeevan-Admin/Ajax-Health-Category-Active']      =   'health_category/activedeactive';

$route['Sujeevan-Admin/Ajax-Health-Category']       =   "common/healthcategory";
$route['Sujeevan-Admin/Ajax-Health-Sub-Category']   =   "common/healthsubcategory";

/** Health Category ****/
$route['Sujeevan-Admin/Ajax-Category-Check']                  =   'health_category/sub_health/unique_category_name'; 
$route['Sujeevan-Admin/Health-Sub-Category']                  =   'health_category/sub_health/index'; 
$route['Sujeevan-Admin/viewsubCategoryHealth/(:num)']         =   'health_category/sub_health/viewsubCategoryHealth/$1'; 
$route['Sujeevan-Admin/Update-Sub-Health-Category/(:any)']    =   'health_category/sub_health/update_category/$1'; 
$route['Sujeevan-Admin/Delete-Sub-Health-Category/(:any)']    =   'health_category/sub_health/delete_category/$1'; 
$route['Sujeevan-Admin/Ajax-Sub-Health-Category-Active']      =   'health_category/sub_health/activedeactivesubcate';

/*** Packages *******/


$route['Sujeevan-Admin/Ajax-Package-CheckValues']       =   'package/unique_packagenametype'; 
$route['Sujeevan-Admin/Package']                  =   'package/index'; 
$route['Sujeevan-Admin/viewPackage/(:num)']       =   'package/viewpackage/$1'; 
// $route['Sujeevan-Admin/Create-Package']           =   'package/create_package'; 
$route['Sujeevan-Admin/Update-Package/(:any)']    =   'package/update_package/$1'; 
$route['Sujeevan-Admin/Delete-Package/(:any)']    =   'package/delete_package/$1'; 
$route['Sujeevan-Admin/Ajax-Package-Active']      =   'package/activedeactive';


$route['Sujeevan-Admin/Ajax-Package-Details-Check']       =   'package_details/unique_package_details_name'; 
$route['Sujeevan-Admin/Package-Details']                  =   'package_details/index'; 
$route['Sujeevan-Admin/viewPackageDetails/(:num)']        =   'package_details/viewpackage_details/$1'; 
$route['Sujeevan-Admin/Create-Package-Details']           =   'package_details/create_package_details'; 
$route['Sujeevan-Admin/Update-Package-Details/(:any)']    =   'package_details/update_package_details/$1'; 
$route['Sujeevan-Admin/Delete-Package-Details/(:any)']    =   'package_details/delete_package_details/$1'; 
$route['Sujeevan-Admin/Ajax-Package-Details-Active']      =   'package_details/activedeactive';



$route['Sujeevan-Admin/Ajax-Category-Check']       =   'category/unique_category_name'; 
$route['Sujeevan-Admin/Category']                  =   'category/index'; 
$route['Sujeevan-Admin/viewCategory/(:num)']       =   'category/viewcategory/$1'; 
$route['Sujeevan-Admin/Create-Category']           =   'category/create_category'; 
$route['Sujeevan-Admin/Update-Category/(:any)']    =   'category/update_category/$1'; 
$route['Sujeevan-Admin/Delete-Category/(:any)']    =   'category/delete_category/$1'; 
$route['Sujeevan-Admin/Ajax-Category-Active']      =   'category/activedeactive';

$route['Sujeevan-Admin/Homecare-Packages']              =   'homecare/homepackages/index'; 
$route['Sujeevan-Admin/viewHomecarepackages/(:num)']    =   'homecare/homepackages/viewHomecarepackages/$1'; 
$route['Sujeevan-Admin/Update-Home-Packages/(:any)']    =   'homecare/homepackages/update_carepackages/$1'; 
$route['Sujeevan-Admin/Delete-Home-Packages/(:any)']    =   'homecare/homepackages/delete_carepackages/$1'; 
$route['Sujeevan-Admin/Ajax-Home-Packages-Active']      =   'homecare/homepackages/activedeactive';
$route['Sujeevan-Admin/Ajax-Package-Check']             =   "homecare/homepackages/unique_package_name";


$route['Sujeevan-Admin/Specialization']                 =   'specialization/index'; 
$route['Sujeevan-Admin/Ajax-Specialization-Check']      =   'specialization/unique_specialization_name'; 
$route['Sujeevan-Admin/viewSpecialization/(:num)']      =   'specialization/viewSpecialization/$1'; 
$route['Sujeevan-Admin/Update-Specialization/(:any)']   =   'specialization/update_specialization/$1'; 
$route['Sujeevan-Admin/Delete-Specialization/(:any)']   =   'specialization/delete_specialization/$1'; 
$route['Sujeevan-Admin/Ajax-Specialization-Active']     =   'specialization/activedeactive';



$route['Sujeevan-Admin/Wellness']                 =   'wellness/index'; 
$route['Sujeevan-Admin/Ajax-Wellness-Check']      =   'wellness/unique_wellness_name'; 
$route['Sujeevan-Admin/viewWellness/(:num)']      =   'wellness/viewWellness/$1'; 
$route['Sujeevan-Admin/Update-Wellness/(:any)']   =   'wellness/update_wellness/$1'; 
$route['Sujeevan-Admin/Delete-Wellness/(:any)']   =   'wellness/delete_wellness/$1'; 
$route['Sujeevan-Admin/Ajax-Wellness-Active']     =   'wellness/activedeactive';

$route['Sujeevan-Admin/Homecare-Tests']              =   'homecare/hometests/index'; 
$route['Sujeevan-Admin/viewHomecaretests/(:num)']    =   'homecare/hometests/viewHomecaretests/$1'; 
$route['Sujeevan-Admin/Update-Home-Tests/(:any)']    =   'homecare/hometests/update_caretests/$1'; 
$route['Sujeevan-Admin/Delete-Home-Tests/(:any)']    =   'homecare/hometests/delete_caretests/$1'; 
$route['Sujeevan-Admin/Ajax-Home-Tests-Active']      =   'homecare/hometests/activedeactive';
$route['Sujeevan-Admin/Ajax-Test-Check']             =   "homecare/hometests/unique_test_name";

$route['Sujeevan-Admin/Create-Works']           =   'homecare/works/create_works'; 
$route['Sujeevan-Admin/Works']                  =   'homecare/works/index'; 
$route['Sujeevan-Admin/viewWorks/(:num)']    	=   'homecare/works/viewWorks/$1'; 
$route['Sujeevan-Admin/Update-Works/(:any)']    =   'homecare/works/update_works/$1'; 
$route['Sujeevan-Admin/Delete-Works/(:any)']    =   'homecare/works/delete_works/$1'; 
$route['Sujeevan-Admin/Ajax-Works-Active']      =   'homecare/works/activedeactive';
/** Health Tips ***/ 
$route['Sujeevan-Admin/Health-Tips']                 =   'healthtips/index'; 
$route['Sujeevan-Admin/viewHealthtips/(:num)']    	 =   'healthtips/viewHealthtips/$1'; 
$route['Sujeevan-Admin/Update-Health-Tip/(:any)']    =   'healthtips/update_healthtips/$1'; 
$route['Sujeevan-Admin/Delete-Health-Tip/(:any)']    =   'healthtips/delete_healthtips/$1'; 
$route['Sujeevan-Admin/Ajax-Health-Tip-Active']      =   'healthtips/activedeactive';

$route['Sujeevan-Admin/Vendors']             	=   'vendors/index'; 
$route['Sujeevan-Admin/Ajax-Vendor-Check']      =   'vendors/unique_vendor_name'; 
$route['Sujeevan-Admin/Create-Vendor']          =   'vendors/create_Vendors'; 
$route['Sujeevan-Admin/viewVendors/(:num)']    	=   'vendors/viewVendors/$1'; 
$route['Sujeevan-Admin/Update-Vendor/(:any)']    =   'vendors/update_vendors/$1'; 
$route['Sujeevan-Admin/Delete-Vendor/(:any)']    =   'vendors/delete_vendors/$1'; 
$route['Sujeevan-Admin/Ajax-Vendor-Active']      =   'vendors/activedeactive';

$route['Sujeevan-Admin/Sub-Vendors']             	=   'vendors/sub_vendors/index'; 
$route['Sujeevan-Admin/Create-Sub-Vendor']          =   'vendors/sub_vendors/create_sub_vendors'; 
$route['Sujeevan-Admin/viewSubVendors/(:num)']    	=   'vendors/sub_vendors/viewsub_vendors/$1'; 
$route['Sujeevan-Admin/Update-Sub-Vendor/(:any)']   =   'vendors/sub_vendors/update_sub_vendors/$1'; 
$route['Sujeevan-Admin/Delete-Sub-Vendor/(:any)']   =   'vendors/sub_vendors/delete_sub_vendors/$1'; 
$route['Sujeevan-Admin/Ajax-Sub-Vendor-Active']     =   'vendors/sub_vendors/activedeactive';


$route['Sujeevan-Admin/Sub-Category']                  =   'sub_category/index'; 
$route['Sujeevan-Admin/viewSubCategory/(:num)']        =   'sub_category/viewsub_category/$1'; 
$route['Sujeevan-Admin/Create-Sub-Category']           =   'sub_category/create_sub_category'; 
$route['Sujeevan-Admin/Update-Sub-Category/(:any)']    =   'sub_category/update_sub_category/$1'; 
$route['Sujeevan-Admin/Delete-Sub-Category/(:any)']    =   'sub_category/delete_sub_category/$1'; 
$route['Sujeevan-Admin/Ajax-Sub-Category-Active']      =   'sub_category/activedeactive';
$route['Sujeevan-Admin/Ajax-Category-List']            =   'sub_category/ajax_category'; 


/*********** Specialization Chat Bot ********/
$route["Sujeevan-Admin/Symptoms-Chat-Bot"]              =   "chat_bot/symptoms/index";
$route["Sujeevan-Admin/viewSymptomsbot/(:num)"]         =   "chat_bot/symptoms/viewSymptomsbot/$1";
$route["Sujeevan-Admin/Delete-SymptomChat-Bot/(:any)"]  =   "chat_bot/symptoms/deletebot/$1";
$route["Sujeevan-Admin/Update-SymptomChat-Bot/(:any)"]  =   "chat_bot/symptoms/updatebot/$1";
$route['Sujeevan-Admin/Ajax-Chat-Symptom-Active']       =   'chat_bot/symptoms/activedeactive';

/** Consult Chat Bot ****/
$route["Sujeevan-Admin/Consult-Chat-Bot"]              =   "chat_bot/consult/index";
$route["Sujeevan-Admin/viewConsultbot/(:num)"]         =   "chat_bot/consult/viewconsultbot/$1";
$route["Sujeevan-Admin/Delete-ConsultChat-Bot/(:any)"]  =   "chat_bot/consult/deletebot/$1";
$route["Sujeevan-Admin/Update-ConsultChat-Bot/(:any)"]  =   "chat_bot/consult/updatebot/$1";
$route['Sujeevan-Admin/Ajax-Chat-Consult-Active']       =   'chat_bot/consult/activedeactive';


/*********** Bot Room ********/
$route["Sujeevan-Admin/Chat-Room-Configuration"]    =   "complaints/botconfiguration/index";
$route["Sujeevan-Admin/viewBotconfig/(:num)"]       =   "complaints/botconfiguration/viewBotconfig/$1";
$route["Sujeevan-Admin/Delete-bot/(:any)"]       =   "complaints/botconfiguration/deleteebot/$1";
$route["Sujeevan-Admin/Update-bot/(:any)"]       =   "complaints/botconfiguration/updatebot/$1";
$route['Sujeevan-Admin/Ajax-bot-Active']         =   'complaints/botconfiguration/activedeactive';


$route['Sujeevan-Admin/Registration']                  =   'registration/index'; 
$route['Sujeevan-Admin/viewRegistration/(:num)']       =   'registration/viewregistration/$1';  
$route['Sujeevan-Admin/Delete-Registration/(:any)']    =   'registration/delete_registration/$1'; 
$route['Sujeevan-Admin/Ajax-Registration-Active']      =   'registration/activedeactive';


$route['Sujeevan-Admin/Specialities']                =   'common/common2/specialities'; 
$route['Sujeevan-Admin/viewSpecialities/(:num)']     =   'common/common2/viewSpecialities/$1';  
$route['Sujeevan-Admin/Packages']                   =   'common/common2/packages'; 
$route['Sujeevan-Admin/viewPackages/(:num)']        =   'common/common2/viewPackages/$1';  
$route['Sujeevan-Admin/Facilities']                =   'common/common2/facilities'; 
$route['Sujeevan-Admin/viewFacilities/(:num)']     =   'common/common2/viewFacilities/$1';  


$route['Sujeevan-Admin/App-Vendors']                =   'app_users/index'; 
$route['Sujeevan-Admin/viewAppvendors/(:num)']      =   'app_users/viewAppvendors/$1';  
$route['Sujeevan-Admin/App-Customers']              =   'app_users/indexcustomers'; 
$route['Sujeevan-Admin/viewAppCustomers/(:num)']    =   'app_users/viewAppCustomers/$1';  
$route['Sujeevan-Admin/Upload-Video-Files']         =   'common/uploaduserfilefiles'; 
$route['Sujeevan-Admin/Remove-Video']               =   'common/removevideo'; 


$route['Sujeevan-Admin/Clearfilter']                =   'common/clearfilter';  
$route['Sujeevan-Admin']        =   'login';  
$route['Forgot-Password']      	=   'login/forgot'; 
$route['Sujeevan-Admin/Ajax-Vendors']      	=   'common/vendorsajax'; 
$route['Ajax-User-Email']      	=   'login/checkemailexist'; 
$route['Ajax-User-Exist']      	=   'login/checkusernameexist'; 
$route['default_controller']    =   'login';

/********** Scores ********/
$route['api-gfr']   = "api/api_scores/gfr";
$route['api-bmi']                   = "api/api_scores/bmi";
$route['api-heart-score']           = "api/api_scores/heart_score";
$route['api-insulin-resistance']    = "api/api_scores/insulin_resistance";


$route['apiregisterview']   = "api/register";
$route['api-send-otp']      = "api/sendotp";
$route['api-otp-verify']    = "api/otp_verify";
$route['apiadddetails']     = "api/update_basic_details";
$route['api-login']         = "api/login";
$route['api-splash']        = "api/splash";
$route['api-logout']        = "api/logout";
$route['api-sub-module']    = "api/submodule";
$route['api-subviewmodule']     = "api/submoduleview";
$route['api-blogs']    		    = "api/blogs";
$route['api-blogs-view']    	= "api/blogsview";
$route['api-questions']    	    = "api/questions";
$route['api-update-details']    = "api/update_basic_details";
$route['api-home-packages']    	= "api/homepackages";
$route['api-home-tests']    	= "api/hometest";
$route['api-packages']          = "api/getPackages";
$route['api-wellness']          = "api/wellness";
$route['api-queries']           = "api/queries";
$route['api-consultation']      = "api/consultation";
$route['api-consultation-view']      = "api/consultationview";
$route['api-chat-room']              = "api/chatroom";
$route['api-symptoms-checker']   = "api/consultdoctors";
$route['api-consult-doctor']     = "api/symptoms_checker";
$route['api-sub-health-symptoms']      =   'api/healthsymptoms';


/*** Vendor ****/
$route['apivendorregister']     = "api/vendor_api/register";
$route['api-send-vendor-otp']   = "api/vendor_api/sendotp";
$route['api-verify-otp']        = "api/vendor_api/otp_verify";
$route['api-vendor-profile']    = "api/vendor_api/vendoriconcreate";
$route['api-vendors']    	    = "api/vendor_api/vendors";
$route['api-masters']   	    = "api/vendor_api/alldata";
$route['api-cities']            = "api/vendor_api/cities";
$route['api-measures']          = "api/vendor_api/measures";
$route['api-doctors']   	    = "api/vendor_api/doctors";
$route['api-vendor-splash']     = "api/vendor_api/splash";
$route['api-vendor-login']      = "api/vendor_api/login";
$route['api-vendor-logout']     = "api/vendor_api/logout";
$route['api-vendor-stage']      = "api/vendor_api/stage_profile";
$route['api-vendor-create']     = "api/vendor_api/create_profile";
$route['api-vendor-rating']     = "api/vendor_api/rating";
$route['api-vendor-support']    = "api/vendor_api/support";
$route['api-vendor-changepassword']      = "api/vendor_api/changepassword";
$route['api-vendor-accounts']   = "api/vendor_api/accounts";
$route['api-vendor-packages']   = "api/vendor_api/packages";
// $route['api-vendor-availbility']    = "api/vendor_api/availbility";
$route['api-vendor-visibility']     = "api/vendor_api/visibility";
$route['api-vendor-blogs']          = "api/vendor_api/blogs";
$route['api-vendor-queries']        = "api/vendor_api/queries";
$route['api-update-profile']        = "api/vendor_api/update_profile";
$route['api-vendor-qualification']  = "api/vendor_api/qualificaitons";
$route['api-vendor-availability']   = "api/vendor_api/register_availability";
$route['api-vendor-earnings']       = "api/vendor_api/earnings";
$route['api-vendor-transaction']    = "api/vendor_api/transaction";
$route['api-vendor-appointments']   = "api/vendor_api/register_appointments";
$route['api-vendor-medications']    = "api/vendor_api/vital_medications";
$route['api-vendor-bp']             = "api/vendor_api/vital_bp";
$route['api-vendor-prescription']   = "api/vendor_api/vital_medicationsothers";
$route['api-vendor-nurse-vitals']   = "api/vendor_api/vital_nursemedications";
$route['api-vendor-products']       = "api/vendor_api/products";
$route['api-vendor-doctors']        = "api/vendor_api/doctors";
$route['api-vendor-specialities']   = "api/vendor_api/specialities";
$route['api-vendor-facilities']     = $route["api-vendor-facilties"]    =    "api/vendor_api/facilites";



// $route['api-signup']    = "api/signup";
// $route['api-content']   = "api/content";
// $route['api-countries'] = "api/countries";
// $route['api-login']     = "api/login";
// $route['api-contact']   = "api/contactus";
// $route['api-forgot']   = "api/forgot";
// $route['api-splash']   = "api/splash";
$route['translate_uri_dashes'] = FALSE;
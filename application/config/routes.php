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
|	https://codeigniter.com/userguide3/general/routing.html
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
$route['default_controller'] = 'frontpage';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['new/application'] = 'Fresh_application/application';
// $route['new/application/start'] = 'Fresh_application/start';
$route['form'] = 'Fresh_application/start';
// $route['new/application/finish'] = 'Fresh_application/finish';
$route['form/finish'] = 'Fresh_application/finish';
// $route['new/application/proceed/(:num)'] = 'Fresh_application/change_section/$1';
$route['form/proceed/(:num)'] = 'Fresh_application/change_section/$1';
// $route['new/application/save'] = 'Fresh_application/save_section_data';
$route['form/save'] = 'Fresh_application/save_section_data';

$route['form/new'] = 'Fresh_application/create_new_application';
$route['signup'] = 'Users/signup';
$route['login'] = 'Users/login';
$route['logout'] = 'Users/logout';
$route['home'] = 'Users/home';
$route['form/excel'] = 'Fresh_application/handle_excel';

$route['signup/process'] = 'Users/signup_process';
$route['login/process'] = 'Users/login_process';
$route['resume/(:num)'] = 'Fresh_application/resume_application/$1';
$route['verify'] = 'Users/send_verification_email';
$route['token/verify'] = 'Users/verify_token';
$route['reset'] = 'Users/recover_password';
$route['reset/process'] = 'Users/generate_password_token';
$route['reset/password'] = 'Users/reset_password';
$route['reset/password/process'] = 'Users/reset_password_process';
$route['process/clarifications/(:num)'] = 'Fresh_application/process_clarifications/$1';
$route['process/viewmode/(:num)'] = 'Fresh_application/process_viewmode/$1';
$route['form/clarifications/save'] = 'Fresh_application/save_clarifications';
$route['refresher/(:num)'] = 'Fresh_application/process_refresher/$1';


//reviewer routes
$route['reviewer'] = 'Reviewer/home';
$route['pull/(:num)'] = 'Reviewer/pull_application/$1';
$route['start/review/(:num)'] = 'Reviewer/start_review/$1';
$route['start/consultation/(:num)'] = 'Reviewer/start_consultation/$1';
$route['process/review'] = 'Reviewer/process_review';
$route['process/(:num)'] = 'Reviewer/change_section/$1';
$route['process/form/update'] = 'Reviewer/save_updated_form';
$route['reviewer/notes/update'] = 'Reviewer/save_notes';
$route['process/review/decision'] = 'Reviewer/process_decision';
$route['process/update/consultation'] = 'Reviewer/change_to_consultation';
$route['process/update/underreview'] = 'Reviewer/change_to_underreview';
$route['process/submit/review'] = 'Reviewer/handle_submit_review';
$route['reviewer/directory'] = 'Reviewer/directory';
$route['reviewer/directory/(:num)'] = 'Reviewer/application_details/$1';
$route['reviewer/experts/update'] = 'Reviewer/update_experts_list';
$route['process/finish/(:num)'] = 'Reviewer/finish_consultation/$1';
$route['reviewer/generate_cron_application/(:num)'] = 'Reviewer/generate_cron_application/$1';



//expert routes
$route['expert'] = 'Expert/home';
$route['start/inputs/(:num)'] = 'Expert/start_inputs/$1';
$route['process/inputs'] = 'Expert/process_inputs';
$route['process/inputs/(:num)'] = 'Expert/change_section/$1';
$route['process/inputs/decision'] = 'Expert/process_inputs_decision';
$route['process/submit/inputs'] = 'Expert/submit_inputs';

//admin routes
$route['admin/dashboard/(:any)'] = 'Admin/home/$1';
$route['admin/dashboard'] = 'Admin/home';
$route['admin/import'] = 'Admin/import_old_data';

$route['admin/import/old_application/(:any)/(:any)'] = 'Admin/import_single_application/$1/$2';
$route['admin/delete/old_application/(:any)/(:any)'] = 'Admin/delete_imported_application/$1/$2';
$route['admin/users'] = 'Admin/users_list';
$route['admin/user/create'] = 'Admin/create_new_user';
$route['admin/user/update'] = 'Admin/update_user';
$route['admin/sections'] = 'Admin/sections_list';
$route['admin/section/create'] = 'Admin/create_new_section';
$route['admin/section/update'] = 'Admin/update_section';

$route['admin/questions'] = 'Admin/questions_list';
$route['admin/questions/(:num)'] = 'Admin/questions_list/$1';
$route['admin/question/create/(:num)'] = 'Admin/create_new_question/$1';
$route['admin/question/update'] = 'Admin/update_question';

$route['admin/applications'] = 'Admin/applications_list';
$route['admin/application/(:num)'] = 'Admin/application_details/$1';
$route['admin/application/update/status'] = 'Admin/update_application';
$route['admin/application/priority/add/(:num)'] = 'Admin/add_priority/$1';
$route['admin/application/priority/remove/(:num)'] = 'Admin/remove_priority/$1';
$route['admin/application/response/edit/(:num)/(:num)/(:num)'] = 'Admin/edit_response/$1/$2/$3';
$route['admin/response/update'] = 'Admin/update_response';
$route['admin/application/update/tags'] = 'Admin/update_tags_string';
$route['admin/logs/(:num)/(:any)/(:any)/(:any)/(:num)'] = 'Admin/logs_data/$1/$2/$3/$4/$5';
$route['admin/logs/(:num)/(:any)/(:any)/(:any)'] = 'Admin/logs_data/$1/$2/$3/$4';
$route['admin/logs/process_filter'] = 'Admin/process_filter';

$route['admin/logs/export/(:num)/(:any)/(:any)/(:any)'] = 'Admin/export_logs_data/$1/$2/$3/$4';

$route['admin/limits'] = 'Admin/manage_limits';
$route['admin/process/limits'] = 'Admin/process_limits';
$route['admin/crons'] = 'Admin/cron_details';









//Public URL
$route['a/(:num)'] = 'General_public/app_public_view/$1';
$route['pages/legal'] = 'General_public/legal_page_view/';


//API routes
$route['api/dpgs'] = 'Api/dpgs';
$route['api'] = 'Api/index';
$route['api/dpg/(:num)'] = 'Api/dpg/$1';
$route['api/nominees'] = 'Api/nominees';
$route['api/nominee/(:num)'] = 'Api/nominee/$1';
















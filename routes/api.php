<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::middleware('auth')->group(function () {
    Route::get('/getUsers', 'UsersController@getUsers');
    Route::get('/user', 'UsersController@fetchLoggedInUserData');
    Route::get('/userEula', 'UsersController@userEula');
    Route::put('/user/{id}', 'UsersController@updateUser');
    Route::post('/user/profile_picture/{id}', 'UsersController@updateUserProfilePicture');
    Route::get('/getBuildingStaffUsers', 'UsersController@getBuildingStaffUsers');
    Route::get('/fetchUserData/{id?}', 'UsersController@fetchUserData');
    Route::post('/change-password', 'UsersController@changePassword');
    Route::post('/addStaffUser', 'UsersController@addStaffUser');
    Route::post('/updateStaffUser/{id?}', 'UsersController@updateStaffUser');
    Route::post('/deleteStaffUser/{id?}/building/{bid?}', 'UsersController@deleteStaffUser');
    Route::post('/checkStaffMobile', 'UsersController@checkStaffMobile');
    Route::post('/checkUserExists', 'UsersController@checkUserExists');
    Route::post('/checkStaffEmail', 'UsersController@checkStaffEmail');
    Route::get('/verifyMobileByOtp/{user_id?}', 'UsersController@verifyMobileByOtp');

    ## BUILDING ROUTES START ##

    Route::get('/getUserTypes/{type}', 'BuildingController@getUserTypes');
    Route::get('/buildings', 'BuildingController@getBuildings');
    Route::get('/getBuildingsForUser', 'BuildingController@getBuildingsForUser');
    Route::get('/loadBuildingDetails/{buildingId}', 'BuildingController@loadBuildingDetails');
    Route::get('/onlyLoadBuildingDetails/{buildingId}', 'BuildingController@onlyLoadBuildingDetails');
    Route::get('/getEntriesPerPage', 'BuildingController@getEntriesPerPage');
    Route::get('/getBuildingsUserTypes', 'BuildingController@getBuildingsUserTypes');
    Route::get('/building/{id?}/units', 'BuildingController@getUnitsOfABuilding');
    Route::get('/getAssignedBuildingsOfAStaffUser/{id?}', 'BuildingController@getAssignedBuildingsOfAStaffUser');
    Route::post('/setBuildingIdInSession', 'BuildingController@setBuildingIdInSession');
    Route::post('/clearBuildingInSession', 'BuildingController@clearBuildingInSession');
    Route::post('/assignBuildings/{id?}', 'BuildingController@assignBuildings');
    Route::any('buildingVitalSigns', 'BuildingController@buildingVitalSigns');
    Route::get('/buildingAlertSettings/{building_id?}/{kind?}', 'BuildingController@alertSettings');
    Route::post('/saveAlertSettings/{building_id?}/{kind?}', 'BuildingController@saveAlertSettings');
    Route::post('/saveFobSettings/{building_id?}', 'BuildingController@saveFobSettings');
    Route::post('/saveBuildingSettings/{building_id?}', 'BuildingController@saveBuildingSettings');
    Route::post('/saveLinkedBuildings/{building_id?}', 'BuildingController@addLinkedBuildings');
    Route::get('/getLinkedBuildings/{building_id?}', 'BuildingController@getLinkedBuildings');
    Route::post('/updateBuildingImage/{building_id?}', 'BuildingController@updateBuildingImage');
    ## BUILDING ROUTES END ##

    Route::post('/building/{id}/lockdown/{is_active}', 'LockdownController@changeLockdownStatus');
    Route::get('/activateOrDeactivateLockdown/{id?}/{value?}', 'LockdownController@activateOrDeactivateLockdown');

    Route::get('/tasks', 'TaskController@index');
    Route::get('/tasks/count', 'TaskController@getTasksCount');
    Route::get('/change-task-state', 'TaskController@changeTaskState');
    Route::get('/getUnitUsers/{id?}', 'TaskController@loadUnitUsers');
    Route::post('/search', 'SearchController@search');

    Route::get('/loadMailServiceProviders', 'AnalyticsController@loadMailServiceProviders');

    Route::get('/loadAmazonUrl', 'AnalyticsController@loadAmazonUrl');

    //Analytics
    Route::post('/unitAnalytics', 'UnitAnalyticsController@index');
    Route::post('/loadUnitActivityData', 'UnitAnalyticsController@loadUnitActivityData');

    Route::post('/userAnalytics', 'UserAnalyticsController@index');
    Route::post('/userActivityModal', 'UserAnalyticsController@userActivityModal');
    Route::post('/loginActivityModal', 'UserAnalyticsController@loginActivityModal');
    Route::post('/userEntranceModal', 'UserAnalyticsController@userEntranceModal');
    Route::post('/userEmailModal', 'UserAnalyticsController@userEmailModal');

    Route::post('/entryControllerAnalytics', 'EntryControllerAnalyticsController@index');

    Route::post('/walletAnalytics', 'WalletAnalyticsController@index');
    Route::post('/walletActivityModal', 'WalletAnalyticsController@walletActivityModal');

    Route::post('/applianceStateAnalytics', 'ApplianceStateAnalyticsController@index');

    Route::post('/deviceAnalytics', 'DeviceAnalyticsController@index');
    Route::post('/loadFiltersForDevices', 'DeviceAnalyticsController@loadFiltersForDevices');

    Route::post('/taskAnalytics', 'TaskAnalyticsController@index');

    Route::post('/packageAnalytics', 'NonAdminAnalyticsController@packageAnalytics');
    Route::post('/lockerPackageDetail', 'NonAdminAnalyticsController@lockerPackageDetail');
    Route::post('/dataAnalytics', 'NonAdminAnalyticsController@dataAnalytics');
    Route::post('/fobsHidAnalytics', 'NonAdminAnalyticsController@fobsHidAnalytics');
    Route::post('/fobsHidImageAnalytics', 'NonAdminAnalyticsController@fobsHidImageAnalytics');

    Route::post('/fobsVersoAnalytics', 'NonAdminAnalyticsController@fobsVersoAnalytics');
    Route::get('/loadEventsDataForVersoReport', 'NonAdminAnalyticsController@loadEventsDataForVersoReport');

    Route::post('/intercomAnalytics', 'NonAdminAnalyticsController@intercomAnalytics');
    Route::post('/laundryAnalytics', 'NonAdminAnalyticsController@laundryAnalytics');
    Route::post('/phoneAnalytics', 'NonAdminAnalyticsController@phoneAnalytics');
    Route::post('/smsAnalytics', 'NonAdminAnalyticsController@smsAnalytics');
    Route::post('/tiltAnalytics', 'NonAdminAnalyticsController@tiltAnalytics');
    Route::post('/uniqueDevicesAnalytics', 'NonAdminAnalyticsController@uniqueDevicesAnalytics');

    Route::post('/transactionAnalytics', 'TransactionAnalyticsController@index');

    Route::post('/loadFiveImages', 'AnalyticsController@loadFiveImages');

    //Analytics

    ## MESSAGES ROUTES START ##

    Route::get('/messages/{type}', 'MessagesController@index');
    Route::get('/messages/user/{id}', 'MessagesController@getChatDetail');
    Route::get('/message/loadUsers', 'MessagesController@loadUsers');
    Route::post('/sendMessage', 'MessagesController@sendMessage');
    Route::get('/message/{id}/archive/{value}', 'MessagesController@archiveMessage');

    ## MESSAGES ROUTES END ##

    ## WORK ORDERS ROUTES START ##

    Route::get('/workOrders', 'WorkOrderController@index');
    Route::post('/archivedWorkOrders', 'WorkOrderController@archivedWorkOrders');
    Route::get('/changeWorkOrderStatus', 'WorkOrderController@changeWorkOrderStatus');
    Route::post('/addAttachment/{id?}', 'WorkOrderController@addAttachments');
    Route::post('/addComment/{id?}', 'WorkOrderController@addComment');
    Route::get('/getMaintenanceAndWatchersList/{id?}', 'WorkOrderController@getMaintenanceAndWatchersList');
    Route::post('/addWorkOrder', 'WorkOrderController@addWorkOrder');
    Route::get('/getIssueTypes', 'WorkOrderController@getIssueTypes');
    Route::get('/getResidentsByUnit/{id}', 'WorkOrderController@getResidentsByUnit');
    Route::post('/workorder/sendEmail/{id}', 'WorkOrderController@sendEmail');
    Route::post('/workorder/downloadWorkOrderPDF/{id}', 'WorkOrderController@downloadWorkOrderPDF');
    Route::get('/updateWorkOrderPriority/{id}/{priority}', 'WorkOrderController@updateWorkOrderPriority');
    Route::get('/getMaintenanceUsers', 'WorkOrderController@getMaintenanceUsers');

    ## WORK ORDER ROUTES END ##

    ## BROADCAST ROUTES START ##

    Route::get('/broadcastHistory', 'BroadcastController@broadcastHistory');
    Route::get('/stopVerificationBroadcast/{id?}', 'BroadcastController@stopVerificationBroadcast');
    Route::get('/workorder/getUserPopupDetails/{id?}/{user_dtl?}', 'BroadcastController@getUserPopupDetails');
    Route::post('/workorder/getResidentsByFilters', 'BroadcastController@getResidentsByFilters');
    Route::post('/sendBroadcastMessage', 'BroadcastController@sendBroadcastMessage');
    Route::post('/createBroadcastTemplate', 'BroadcastController@createBroadcastTemplate');
    Route::get('/getBroadcastTemplates', 'BroadcastController@getBroadcastTemplates');

    ## BROADCAST ROUTES END ##


## Units Routes Start
    Route::post('units', 'UnitsController@index');
    Route::post('loadUnverifiedUsers', 'UnitsController@loadUnverifiedUsers');
    Route::post('updateUnitColumn', 'UnitsController@updateUnitColumn');
    Route::post('loadBuildingsByUnit', 'UnitsController@loadBuildingsByUnit');
    Route::get('unit/{unit_id}', 'UnitsController@loadUnitDetails');
    Route::post('relocateResident', 'UnitsController@relocateResident');
    Route::post('getNextAndPreviousUnit', 'UnitsController@getNextAndPreviousUnit');
    Route::post('vacateUnit', 'UnitsController@vacateUnit');
    Route::post('disableServices', 'UnitsController@disableServices');
    Route::post('leaseHistory', 'UnitsController@leaseHistory');
    Route::post('startonboardingprocess', 'UnitsController@startonboardingprocess');
    Route::post('sendDocumentEmails', 'UnitsController@sendDocumentEmails');
    Route::post('cancelOnboardingDocument', 'UnitsController@cancelOnboardingDocument');
    Route::post('updateDoorIntercom', 'UnitsController@updateDoorIntercom');
    Route::post('moveoutuser', 'UnitsController@moveoutuser');
    Route::get('un_link_unit/{unit_id}/{user_id}', 'UnitsController@un_link_unit');
    Route::get('makeGuarantorOrCoguarantor/{unit_id}/{user_id}/{type?}', 'UnitsController@makeGuarantorOrCoguarantor');
    Route::get('switchGuarantorCoGuarantor/{unit_id}', 'UnitsController@switchGuarantorCoGuarantor');
    Route::get('getDocumentPreview/{docId}', 'UnitsController@getDocumentPreview');


## Units Routes End
    ## Financial Routes Start## 

    Route::post('/getMyAllWallets/{id?}/{portfolio?}/{origin?}', 'FinancialController@getMyAllWallets');
    Route::any('buildingAccountAssignment/{buildingId}/{walletId}/{userId}/{transSourceId}/{portfolio}/{defaultAreaStatus}', 'FinancialController@buildingAccountAssignment');
    Route::post('/getUserWallets/{id?}', 'WalletController@getUserWallets');
    Route::post('wallet/store/{userId}/{portfolio?}/{origin?}', 'WalletController@store');
    Route::post('wallet/update/{walletId}/{portfolio?}/{origin?}', 'WalletController@update');
    Route::get('wallet/sendOTP/{userId}', 'WalletController@sendOTP');
    Route::get('wallet/resend_OTP/{userId}', 'WalletController@resendOTP');
    Route::get('wallet/{id?}/delete', 'WalletController@destroy');
    Route::post('loadPayables', 'FinancialController@loadPayables');
    Route::post('loadAchStatusData', 'FinancialController@loadAchStatusData');
    Route::post('sendOneTimeInvoice', 'FinancialController@sendOneTimeInvoice');
    Route::get('getUserTypesForReceivablesAndPayables', 'FinancialController@getUserTypesForReceivablesAndPayables');
    Route::get('getUsersOfABuildingByUserType/{building_id?}/{user_id?}', 'FinancialController@getUsersOfABuildingByUserType');
    Route::get('getPayer/{building_id?}/{user_id?}', 'FinancialController@getPayer');
    Route::post('loadFinanceSummary', 'FinancialController@loadFinanceSummary');
    Route::post('loadOwnerOrderSummary', 'FinancialController@loadOwnerOrderSummary');
    Route::post('financeLastTransactions', 'FinancialController@financeLastTransactions');
    Route::post('ownerReceivables', 'FinancialController@ownerReceivables');


    ## Financial Routes end ##


    ## Financial Routes Start##

    Route::get('/cameras/{buildingId?}', 'CameraController@index');
    Route::get('/cameraDetails/{cameraId?}', 'CameraController@cameraDetails');
    ## Financial Routes end ##

    ## Residents Route Start ##
    Route::post('residents', 'ResidentController@index');
    Route::get('resident/{user_id?}', 'ResidentController@loadResidentData');
    Route::get('getEntrancesList/{unit_id?}', 'ResidentController@getEntrancesList');
    Route::get('getStorageAndParkingUnits/{user_id?}/{building_id?}/{type?}', 'ResidentController@getStorageAndParkingUnits');
    Route::post('addNewMacAddress', 'ResidentController@addNewMacAddress');
    Route::post('addNewStorageAndParking', 'ResidentController@addNewStorageAndParking');
    Route::get('loadAllTokens/{building_id?}/{user_id?}', 'ResidentController@loadAllTokens');
    Route::post('updateFobAssignment', 'ResidentController@updateFobAssignment');
    Route::post('addResident', 'ResidentController@addResident');
    Route::any('searchUsers', 'ResidentController@searchUsers');
    Route::any('checkResidentMobileExists', 'ResidentController@checkResidentMobileExists');
    Route::any('checkResidentEmailExists', 'ResidentController@checkResidentEmailExists');
    Route::any('resendVerificationLink/{userId}', 'ResidentController@resendVerificationLink');
    Route::any('manuallyVerifyUser/{userId}', 'ResidentController@manuallyVerifyUser');


    ## Residents Route End ##

    ## LOCKERS ROUTES START ##
    Route::post('lockers', 'LockersController@index');
    Route::post('openLocker', 'LockersController@openLocker');
    Route::post('lockerPackageExtension', 'LockersController@lockerPackageExtension');
    Route::post('viewExtensionHistory', 'LockersController@viewExtensionHistory');
    Route::post('sendPackageAlert', 'LockersController@sendPackageAlert');
    ## LOCKERS ROUTES END ##


    ## DOORS ROUTES START ##

    Route::get('doors/{building_id?}', 'DoorsController@index');
    Route::get('/asset/door_status/{asset_id}/{action}', 'DoorsController@doorStatus');
    Route::get('/asset/schedule/{asset_id}/{schedule_id}', 'DoorsController@assignSchedule');

    ## DOORS ROUTES END ##

    ## FOBS ROUTES START ## 

    Route::post('/get_assigned_fobs', 'FobsController@getAssignedFobs');
    Route::post('/get_unassign_fobs', 'FobsController@getUnAssignedFobs');
    Route::post('/getDiscontinuedFobs', 'FobsController@getDiscontinuedFobs');
    Route::get('/remove_and_recycle/{id?}', 'FobsController@remove_and_recycle');
    Route::get('/remove_permanently/{id?}', 'FobsController@remove_permanently');
    Route::post('/get_entry_activity/{id?}', 'FobsController@get_entry_history');
    Route::post('/get_fob_history/{id?}', 'FobsController@get_fob_history');
    Route::get('/fobs/loadUsers', 'FobsController@loadUsers');

    ## FOBS ROUTES END ##

    ## Storage Routes START ## 
    Route::get('/storage', 'StorageController@index');
    Route::get('/getStorageBuildings', 'StorageController@getStorageBuildings');
    Route::get('/vacate/storage/{id?}', 'StorageController@vacateResident');
    Route::get('/storage/loadUsers', 'StorageController@loadUsers');
    Route::post('/storage/assign', 'StorageController@assignStorage');

    ## Storage Routes END ##

    ## Parking Routes Start ## 

    Route::get('/parking', 'ParkingController@index');
    Route::get('/getParkingBuildings', 'ParkingController@getParkingBuildings');
    Route::get('/parking/loadUsers', 'ParkingController@loadUsers');
    Route::post('/parking/assign', 'ParkingController@assignParking');
    Route::post('/vacateResidents/{unit_id?}', 'ParkingController@vacateResidents');

    ## Parking routes end ## 

    ##  Owners Start ##

    Route::post('/owners', 'AdminActionsController@owners');

    ##  Owners End ##

    ##  Non Performance Start ##

    Route::post('/nonPerformance', 'NonPerformanceController@index');
    Route::post('/loadSequenceData', 'NonPerformanceController@loadSequenceData');

    ##  Non Performance End ##

    ##  Offerings Start ##

    Route::post('/loadResidentOfferings', 'OfferingsController@index');
    Route::post('/loadOwnerOfferings', 'OfferingsController@ownerOfferings');

    ##  Offerings End ##

    Route::post('/getPermissions', 'AclController@getPermissions');
    ## LAUNDRY ROUTES START ##

    Route::get('/loadLaundryData/{building_id}', 'LaundryController@index');
    Route::get('/getLaundryHistory/{id}', 'LaundryController@showHistory');
    Route::get('/laundries/setNewState/{id}/{state}/{isBoth}', 'LaundryController@setNewState');
    Route::get('/laundry_change_reservable/{id}/{state}', 'LaundryController@laundry_change_reservable');
    Route::get('/seeReservation/{id}/{type?}', 'LaundryController@viewreservation');
    Route::get('/create_weekly_planner_html/{id?}', 'LaundryController@create_weekly_planner_html');
    Route::post('/laundry_change_status/{id}', 'LaundryController@laundry_change_status');

    Route::post('/saveLaundryName', 'LaundryController@saveLaundryName');
    Route::post('/saveApplianceProfileTemplate', 'LaundryController@saveApplianceProfileTemplate');
    Route::get('/loadApplianceProfileTemplateData', 'LaundryController@loadApplianceProfileTemplateData');
    Route::get('/loadApplianceProfileTemplateData/{id?}/{show_switcher?}', 'LaundryController@laundry_copyprofile_template_edit');
    Route::post('/validateExceptionalDiscount', 'LaundryController@validateExceptionalDiscount');
    Route::post('/laundry_validate_cost_other_weekdays_cp', 'LaundryController@laundry_validate_cost_other_weekdays_cp');
    Route::post('/laundry_set_cost_data_cp', 'LaundryController@laundry_set_cost_data_cp');
    Route::post('/laundry_set_discount_data_cp', 'LaundryController@laundry_set_discount_data_cp');
    Route::post('/laundry_set_discount_data', 'LaundryController@laundry_set_discount_data');
    Route::get('/deleteLaundryDiscount/{id?}', 'LaundryController@deleteLaundryDiscount');
    Route::get('/laundry_washer_dryer_new_cost/{room_id?}/{washer_dryer_id?}', 'LaundryController@laundry_washer_dryer_new_cost');
    Route::post('/laundry_save_all_charges', 'LaundryController@laundry_save_all_charges');
    Route::post('/laundry_set_cost_data', 'LaundryController@laundry_set_cost_data');

    Route::post('/saveAdvertisement', 'LaundryController@saveAdvertisement');
    Route::get('/getAdvertisements', 'LaundryController@getAdvertisements');
    Route::post('/addMoneyLaundry', 'LaundryPaymentController@addMoneyLaundry');
    Route::post('/startAppliance', 'LaundryPaymentController@startAppliance');

    ## LAUNDRY ROUTES END ## 

    ## All logs ROUTES START
    Route::post('/allLogs', 'AllLogsController@index');
    Route::post('/aclLogs', 'AllLogsController@aclLogs');
    Route::post('/cronLogs', 'AllLogsController@cronLogs');
    Route::post('/extensionLogs', 'AllLogsController@extensionLogs');
    Route::post('/loginLogs', 'AllLogsController@loginLogs');
    Route::post('/paymentLogs', 'AllLogsController@paymentLogs');
    Route::post('/systemLogs', 'AllLogsController@systemLogs');
    Route::post('/unitLogs', 'AllLogsController@unitLogs');
    Route::post('/walletLogs', 'AllLogsController@walletLogs');
    Route::post('/workOrderLogs', 'AllLogsController@workOrderLogs');
    Route::post('/lockerLogs', 'AllLogsController@lockerLogs');
    Route::post('/errorLogs', 'AllLogsController@errorLogs');
    Route::post('/activityLogs', 'AllLogsController@activityLogs');

    ## All logs ROUTES END

    ## Sub Roles ROUTES START

    Route::post('/loadSubRoles', 'SubRolesController@index');
    Route::post('/staffSubRoles', 'SubRolesController@staffSubRoles');
    Route::post('/addNewSubRoleModal', 'SubRolesController@addNewSubRoleModal');
    Route::post('/assignNewSubRole', 'SubRolesController@assignNewSubRole');
    Route::post('/deleteSubRoleAssignment', 'SubRolesController@deleteSubRoleAssignment');

    ##Sub Roles ROUTES END

    ## Sub Roles ROUTES START

    Route::post('/loadSystemUsers', 'SystemUsersController@index');

    ##Sub Roles ROUTES END

});

<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\BuildingsUsers;
use App\Models\ChangeEmail;
use App\Models\EulaVersion;
use App\Models\OtpCode;
use App\Models\ShaHasher;
use App\Models\UnitUser;
use App\Models\User;
use App\Models\UserToken;
use App\Models\UserTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    private $staff_usertypes = array('Agent', 'Custodian', 'Mail', 'Maintenance', 'Manager', 'First Responder');

    public function fetchLoggedInUserData()
    {
        return User::with(['usertype', 'units' => function ($q) {
            $q->select('units.id', 'buildings.id as building_id', 'buildings.name as building_name', 'units.unit_no')->join('buildings', 'buildings.id', '=', 'units.building_id')->where('units.is_physical', 1);
        }])->where('id', Auth::id())->first();
    }

    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'dob' => 'required',
            'mobile' => 'required',
        ]);

        $requestData = $request->all();

        $user = User::find($id);
        if ($user) {
            $old_mobile = $user['mobile'];
            $old_email = $user['email'];


            if (trim($user['mobile']) != trim($requestData['mobile'])) {
                $requestData['mobile_verification'] = "No";
            } elseif ($user['mobile_verification'] == 'Yes') {
                $requestData['mobile_verification'] = "Yes";
            }

            if (trim($user['email']) != trim($requestData['email'])) {
                $requestData['verification_email'] = UserToken::email_token(10);
                $requestData['email_verified'] = 0;
                $requestData['old_email'] = $user['email'];
            } elseif ($user['email_verified'] == 1) {
                $requestData['email_verified'] = 1;
            }

            $user->update($requestData);
            if ($old_mobile != trim($requestData['mobile'])) {
                // $this->unit_log($this->User->id, 10, 'Mobile Number Changed', ['old' => $old_mobile, 'new' => $receivedData['mobile']], $this->Session->read('bld'), $parent_unit_id);
                addUnitsLog($user['id'], 10, 'Mobile Number Changed', ['old' => $old_mobile, 'new' => $requestData['mobile']], session('building_id') ?? 0, $user['parent_unit_id'], 0, $user['id'], Auth::id());
                $smsMessage = "Your mobile number was changed from " . $old_mobile . " to " . $requestData['mobile'] . ". If you did not authorize, log into your Urban Sky account change your password and mobile asap.\n";
                try {
                    sendSMS($smsMessage, "+1" . str_replace("-", "", $user['mobile']));
                    sendSMS($smsMessage, "+1" . str_replace("-", "", $old_mobile));
                    sendEmail($smsMessage, $user['email'], null, "Mobile number changed", '', false);
                } catch (\Exception $exc) {

                }

            }
            // send email to old and new address if changed
            if (trim($requestData['email']) != trim($old_email)) {
                // $this->unit_log($this->User->id, 10, 'Email Address Changed', ['old' => $old_email, 'new' => $receivedData['email']], $this->Session->read('bld'), $parent_unit_id);

                addUnitsLog($user['id'], 10, 'Email Address Changed', ['old' => $old_email, 'new' => $requestData['email']], session('building_id') ?? 0, $user['parent_unit_id'], 0, $user['id'], Auth::id());
                ChangeEmail::create([
                    'user_id' => $id,
                    'new_email' => $requestData['email'],
                    'email_token' => $user['verification_email'],
                    'verified' => 0,
                    'createddate' => date('Y-m-d'),
                ]);

                $base = config('app.portal_url') . '/';
                $activation_link = $base . 'users/change_email_verification/' . $user['verification_email'] . '/' . $id;
                $subject = "Confirmation email address change";
                $body = "<body> 
                            <P>Hello " . $user['full_name'] . ",</P>
                            <p>Your email verification link for Urbansky is <a href=" . $activation_link . ">" . $activation_link . "</a></p> <p>Please click on the link and verify your change email address</p>
                            <p>Regard,<br>
                             MyUrbansky</p> 
                            </body>";

                $body2 = "<body> 
                            <P>Hello " . $user['full_name'] . ",</P>
                            <p>Your email was changed to " . $user['email'] . "</p> <p>If you did not authorize, log into your Urban Sky account change your password and email address asap.</p>
                            <p>Regard,<br>
                             MyUrbansky</p> 
                            </body>";

                try {

                    sendEmail($body, $requestData['email'], null, $subject, '', false);
                    sendEmail($body2, $old_email, null, $subject, '', false);


                    $smsMessage = "Your email was changed to " . $requestData['email'] . " If you did not authorize, log into your Urban Sky account change your password and email address asap.";
                    sendSMS($smsMessage, "+1" . str_replace("-", "", $user['mobile']));
                } catch (\Exception $exc) {

                }
            }


            $user = User::with(['usertype'])->where('id', $id)->first();

            return response()->json(['data' => $user]);
        }
        return response()->json(['data' => 'User not found']);
    }

    public function updateUserProfilePicture(Request $request, $id)
    {
        $request->validate([
            'profilePicture' => 'required'
        ]);
        User::where('id', $id)->update(['profile_picture' => $request->profilePicture]);
        return response()->json('Profile picture updated successfully.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'new_password' => 'required|min:8',
        ]);
        $current_password = config('services.salt', '') . $request->password;

        $user = User::find(Auth::id());

        if (sha1($current_password) == $user->password) {
            $user->update([
                'password' => sha1(config('services.salt', '') . $request->new_password),
            ]);

            return response()->json(['code' => 1, 'message' => 'Password changed successfully.']);
        }

        return response()->json(['code' => 0, 'message' => 'Incorrect current password.']);
    }

    public function getUsers()
    {
        $buildings = Auth::user()->buildingIds();
        $buildingUsers = BuildingsUsers::whereIn('building_id', $buildings)->pluck('user_id')->toArray();
        $unitUsers = UnitUser::join('units', 'units.id', '=', 'units_users.unit_id')->join('buildings', 'buildings.id', '=', 'units.building_id')
            ->whereIn('buildings.id', $buildings)->pluck('units_users.user_id')->toArray();
        $users = User::with('unit', 'usertype')->whereIn('id', $buildingUsers)->orWhereIn('id', $unitUsers)->get();

        foreach ($users as $index => $user) {
            $fullName = $user->name;
//            if (isset($user->unit->unit_no)) {
//                $fullName = $fullName . " - " . $user->unit->unit_no;
//            }
            if (isset($user->usertype)) {
                $fullName = $fullName . " - " . $user->usertype->name;
            }
            $user->full_name = $fullName;
            $users[$index] = $user;
        }

        return response()->json([
            "status" => true,
            "data" => $users
        ]);
    }

    public function getBuildingStaffUsers(Request $request)
    {
        $building_id = $request->building_id;

        $staff_usertypes = $this->staff_usertypes;

        if ($request->has('type') && $request->type != 'All' && $request->type != '') {
            $staff_usertypes = [$request->type];
        }

        $usertype_ids = UserTypes::where('status', 1)->whereIn('name', $staff_usertypes)->pluck('id')->toArray();


        $building_users = BuildingsUsers::select('users.*', 'usertypes.name as usertype_name')->leftJoin('users', 'users.id', '=', 'buildings_users.user_id')
            ->leftJoin('usertypes', 'usertypes.id', '=', 'users.usertype_id')
            ->whereIn('users.usertype_id', $usertype_ids)
            ->select('users.*', DB::raw("CONCAT(users.firstname, ' ', users.lastname) as fullname"))
            ->where('buildings_users.building_id', $building_id)->distinct()->get();
        return response()->json(['data' => $building_users]);
    }

    public function fetchUserData($id)
    {
        return User::with(['usertype', 'buildings'])->where('id', $id)->first();
    }

    public function userEula(Request $request)
    {
        $user = Auth::user();
        $eula_version = $user->accpeted_eula_version;
        $eula_data = EulaVersion::where('version', $eula_version)->first();
        $path_server = url("/files/docsign/EULA_files/");
        if ($eula_data) {
            $file_path_server = $path_server . "/" . $eula_data->name;
        }

        return response()->json([
            "status" => true,
            "data" => $file_path_server ?? ''
        ]);
    }

    public function addStaffUser(Request $request)
    {
        $request->validate([
            'usertype' => 'required',
            'email' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'building' => 'required'
        ]);

        $user = User::create([
            'prefix' => $request->prefix ?? '',
            'firstname' => $request->firstname ?? '',
            'lastname' => $request->lastname ?? '',
            'mobile' => $request->mobile ?? '',
            'dob' => $request->dob ?? '',
            'city' => $request->address1 ?? '',
            'state' => $request->state ?? '',
            'zipcode' => $request->zipcode ?? '',
            'email' => $request->email ?? '',
            'address1' => $request->address1 ?? '',
            'usertype_id' => $request->usertype,
            'profile_picture' => $request->profile_picture
        ]);

        foreach ($request->building as $building) {
            BuildingsUsers::create([
                'building_id' => $building['id'],
                'user_id' => $user->id
            ]);
        }
    }

    public function updateStaffUser(Request $request, $id)
    {
        $request->validate([
            'usertype' => 'required',
            'email' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            // 'building' => 'required'
        ]);
        $user = User::find($id);
        $user->update([
            'prefix' => $request->prefix ?? '',
            'firstname' => $request->firstname ?? '',
            'lastname' => $request->lastname ?? '',
            'mobile' => $request->mobile ?? '',
            'dob' => $request->dob ?? '',
            'city' => $request->address1 ?? '',
            'state' => $request->state ?? '',
            'zipcode' => $request->zipcode ?? '',
            'email' => $request->email ?? '',
            'address1' => $request->address1 ?? '',
            'usertype_id' => $request->usertype,
            'profile_picture' => $request->profile_picture
        ]);

        BuildingsUsers::where('user_id', $user->id)->delete();
        if ($request->has('building')) {
            foreach ($request->building as $building) {
                BuildingsUsers::updateOrCreate([
                    'building_id' => $building['id'],
                    'user_id' => $user->id
                ],
                    [
                        'building_id' => $building['id'],
                        'user_id' => $user->id
                    ]);
            }
        }
    }

    public function deleteStaffUser($user_id, $building_id)
    {
        return BuildingsUsers::where('user_id', $user_id)->where('building_id', $building_id)->delete();
    }

    public function checkStaffMobile(Request $request)
    {
        $data = $request->all();
        $mobile = $data['mobile'] ?? '';
        $bypass_user = $data['bypass_id'] ?? '';

        $selectedBuilding = $request->building_id ?? 0;

        $building = Building::select('id', 'user_id')->where('id', $selectedBuilding)->first();

        $conditions = [['mobile', $mobile]];
        if ($bypass_user != '') {
            $conditions[] = ['id', '!=', $bypass_user];
        }

        $userdata = User::where($conditions)->first();

        if (!empty($userdata)) {
            if ($building && $userdata->owner_id != $building->user_id) {
                $msg = "Mobile in use already. Please contact Admin.";
                return response()->json(['status' => 2, 'msg' => $msg]);
            } else {
                $msg = "Mobile already exists.";
                return response()->json(['status' => 1, 'msg' => $msg]);
            }
        } else {
            return response()->json(['status' => 0]);
        }

        return response()->json(['status' => -1]);
    }

    public function checkUserExists(Request $request)
    {
        $lastname = $request->input('lastname', '');
        $firstname = $request->input('firstname', '');
        $mobile = rtrim($request->input('mobile', ''), '-');
        $email = $request->input('email', '');
        $staff = $request->input('staff', '');

        $conditions = [];

        if ($lastname != '') {
            $conditions[] = ['lastname', 'LIKE', '%' . $lastname . '%'];
        }

        if ($firstname != '') {
            $conditions[] = ['firstname', 'LIKE', '%' . $firstname . '%'];
        }

        if ($mobile != '' && $mobile != '-') {
            $conditions[] = ['mobile', 'LIKE', '%' . $mobile . '%'];
        }

        if ($email != '') {
            $conditions[] = ['email', 'LIKE', '%' . $email . '%'];
        }

        if ($request->has('building_id')) {
            $bld = $request->building_id ?? '';
            $building = Building::where('id', $bld)->first();

            if ($building && isset($building->user_id)) {
                $owner_id = $building->user_id;
                $conditions[] = ['owner_id', '=', $owner_id];
            }
        }

        $users = User::where($conditions)->limit(20)->get();

        if ($users->isNotEmpty()) {
            return response()->json(['users' => $users, 'owner_id' => $owner_id]);
        } else {
            return response('');
        }
    }

    public function checkStaffEmail(Request $request)
    {
        $emailaddress = $request->input('email') ?? '';
        $bypass_user = $request->input('bypass_id') ?? '';

        $selectedBuilding = $request->building_id ?? '';
        $building = Building::where('id', $selectedBuilding)->first(['id', 'user_id']);

        $conditions = [
            ['email', '=', $emailaddress]
        ];

        if ($bypass_user != '') {
            $conditions[] = ['id', '!=', $bypass_user];
        }

        $userdata = User::where($conditions)->first();

        if (!empty($userdata)) {
            if ($building && $userdata->owner_id != $building->user_id) {
                $msg = "Email in use already. Please contact Admin.";
                return response()->json(['status' => 2, 'msg' => $msg]);
            } else {
                $msg = "Email already exists.";
                return response()->json(['status' => 1, 'msg' => $msg]);
            }
        } else {
            return response()->json(['status' => 0]);
        }
    }

    public function verifyMobileByOtp(Request $request, $user_id)
    {
        $user = User::find($user_id);

        if ($user) {
            $code = OtpCode::where('user_id', $user_id)->where('expire_at', '>', date("Y-m-d H:i:s"))->first();

            $smsMessage = "Please enter the 5 digit password provided by your management or your lastname. It is valid for 2 minutes.";
            $expire_at = date("Y-m-d H:i:s", strtotime("+2 minutes"));

            if (!$code) {
                $token = UserToken::ozekiOTP(5);

                $data['user_id'] = $user_id;
                $data['code'] = $token;
                $data['type'] = 'mobile';
                $data['expire_at'] = $expire_at;
                $data['created_by'] = Auth::id();
                $data['created_at'] = date("Y-m-d H:i:s");

                OtpCode::create($data);
                $response = sendSMS($smsMessage, "+1" . str_replace("-", "", $user['mobile']));
                if ($response) return response()->json(['status' => 'success', 'token' => $token, 'expires_at' => $expire_at]);
                else return response()->json(['status' => 'warning', 'message' => 'Unable to send OTP']);
            } else {
                return response()->json(['status' => 'success', 'token' => $code['code'], 'expires_at' => $code['expire_at']]);
            }
        }

        return response()->json(['status' => 'warning', 'message' => 'Unable to find user.']);
    }

}

<?php

use App\Libs\BrivoApi;
use App\Models\AccessPermission;
use App\Models\AchBatch;
use App\Models\Building;
use App\Models\Configurations;
use App\Models\LinkedBuildings;
use App\Models\Schedule;
use App\Models\ScheduleException;
use App\Models\Tasks;
use App\Models\TokenHistory;
use App\Models\TransactionLog;
use App\Models\Unit;
use App\Models\UnitHistory;
use App\Models\User;
use App\Models\UserTypes;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Twilio\TwiML\Voice\Task;

if (!function_exists('getEntriesPerPage')) {
    function getEntriesPerPage()
    {
        return getConfiguration('entriesPerPage') ?? 30;
    }
}
if (!function_exists('getConfiguration')) {
    function getConfiguration($key)
    {
        return Configurations::where('name', $key)->first()->value ?? null;
    }
}
if (!function_exists('getUnitUsers')) {
    function getUnitUsers($unitId)
    {
        $units = Unit::with('users')
            ->where('id', $unitId)
            ->get();

        return $units;
    }
}


if (!function_exists('applyPercent')) {
    function applyPercent($value, $percent, $number = false)
    {
        if ($number) {
            return $value * ($percent / 100);
        }
        return number_format($value * ($percent / 100), 2);
    }
}

function hasPermission($permission, $item = null)
{
    $permission = AccessPermission::where('alias', $permission)->first();
    $permissions = [];
    $user = null;
    if (isset($item)) {
        if (get_class($item) == "App\User") {
            $userType = $item->usertype;
            $user = $item;
        } else
            $userType = $item;
    } else {
        if (\Illuminate\Support\Facades\Auth::id()) {
            if (\Illuminate\Support\Facades\Auth::user()->isSuperAdmin())
                return true;
        }
        $userType = \Illuminate\Support\Facades\Auth::user()->usertype;
        $user = \Illuminate\Support\Facades\Auth::user();
    }
    if ($userType) {
        if ($userType->accessPermissions)
            $permissions = $userType->accessPermissions->pluck('permission_id')->toArray();
    }
    if ($user) {
        if ($user->accessPermissions)
            $permissions = array_merge($user->accessPermissions->pluck('permission_id')->toArray());
    }
    if (!$permission) {
        return true;
    }
    if ($userType) {
        if (in_array($permission->id, $permissions))
            return true;
    }
    return false;
}

function getUserPermissions()
{
    $user = User::with('accessPermissions', 'usertype.accessPermissions')->where('id', Auth::id())->first();
    if ($user->isSuperAdmin()) {
        $permissions = AccessPermission::pluck('alias')->toArray();
    } else {
        $userPermissions = $user->accessPermissions->pluck('permission_id')->toArray() ?? [];
        $permissions = AccessPermission::whereIn('id', $userPermissions)->pluck('alias')->toArray();
    }

    if (config('app.DISABLE_ACL')) $permissions = AccessPermission::pluck('alias')->toArray();

    return $permissions;
}

function getAuthUser()
{
    $building = getBuildingDetails();
    $user = Auth::user();
    $user->selectedBuilding = $building;
    return $user;
}

if (!function_exists('checkIfBuildingBelongsToOwner')) {
    function checkIfBuildingBelongsToOwner($buildingId, $userId = null)
    {
        if (!$userId) {
            $userId = \Illuminate\Support\Facades\Auth::id();
        }
        $building = Building::find($buildingId);
        if (!$building)
            return false;

        return $userId == $building->user_id;
    }
}


function generatePaymentLinkToken()
{

    return \Illuminate\Support\Str::random(12);

}


if (!function_exists('moveCartsToNewUnit')) {
    function moveCartsToNewUnit($oldUnit, $newUnit)
    {
        $newUnit->rent_balance = $newUnit->rent_balance + $oldUnit->rent_balance;
        $newUnit->amenity_balance = $newUnit->rent_balance + $oldUnit->amenity_balance;
        $newUnit->laundry_balance = $newUnit->rent_balance + $oldUnit->laundry_balance;
        $newUnit->save();
        $oldUnit->rent_balance = 0;
        $oldUnit->amenity_balance = 0;
        $oldUnit->laundry_balance = 0;
        $oldUnit->save();
    }
}


function getFiveImages($image)
{
    $imagesArray = [];
    $image = explode('.', $image);
    $extension = $image[1] ?? null;
    $imageName = $image[0] ?? null;
    if ($imageName) {
        $imageName = substr($imageName, 0, -1);
        for ($index = 0; $index < 5; $index++) {
            $imagesArray[] = $imageName . $index . '.' . $extension;
        }

        return $imagesArray;
    }

    return [];
}

function addUserTokenHistory($user_id, $unit_id, $building_id, $author_id)
{
    $tokens = \App\Models\Token::where('user_id', $user_id)->get();

    foreach ($tokens as $token) {

        $fob_text = $token->mfob_status == 0 ? 'FOB ' : 'mFOB ';
        $TokenHistory['token_id'] = $token->id;
        $TokenHistory['unit_id'] = $unit_id;
        $TokenHistory['building_id'] = $building_id;
        $TokenHistory['user_id'] = $user_id;
        $TokenHistory['action_date'] = date("Y-m-d H:i:s");
        $TokenHistory['action_type'] = 6;
        $TokenHistory['action'] = $fob_text . $token->card_id . ' Trashed ';
        $TokenHistory['action_by'] = $author_id;
        \App\Models\TokenHistory::create($TokenHistory);

    }

}

function getConfigExceptHash($config)
{
    return rtrim(config($config), '/');
}

function doURL($url = NULL, $post = array())
{
    if (is_null($url)) return false;
    $process = curl_init() or die("Init Error");
    if (count($post)) {
        $flds = http_build_query($post);
        curl_setopt($process, CURLOPT_POSTFIELDS, $flds);
        curl_setopt($process, CURLOPT_POST, count($post));
    }

    curl_setopt($process, CURLOPT_URL, $url);
    curl_setopt($process, CURLOPT_HEADER, 0);
    curl_setopt($process, CURLOPT_TIMEOUT, 30);
    curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($process, CURLOPT_SSL_VERIFYPEER, FALSE);
    $return = curl_exec($process) or curl_error($process);

    return $return;
}

if (!function_exists('getAllSystemUsersExceptOwner')) {
    function getAllSystemUsersExceptOwner()
    {
        $users = \App\Models\User::whereIn('usertype_id', [1, 3, 4, 12, 23, 24, 33, 2, 34])->get();

        return $users;
    }
}

if (!function_exists('formatMoneyWithCommas')) {
    function formatMoneyWithCommas($amount)
    {
        return isset($amount) ? '$' . number_format((float)$amount, 2) : '-';
    }
}


function getLastACHBatchDate()
{
    $lastBatch = AchBatch::orderBy('id', 'desc')->first();
    return $lastBatch->created_at ?? null;
}

if (!function_exists('getFeatureGroupsSettings')) {
    function getFeatureGroupsSettings($building_id)
    {
        $packageSettings = DB::table('building_package_addons')->selectRaw('group_id,cart_type')->where('building_id', $building_id)->groupBy('group_id')->get();

        $keyed = $packageSettings->mapWithKeys(function ($item) {
            return [$item->group_id => $item->cart_type];
        });

        $packageSettings = $keyed->all();

        return $packageSettings;
    }
}


/**
 * @param $key
 * @return string
 */
function getConfigurations($key)
{
    $conf = \App\Models\Configurations::where('name', $key)->first();

    return $conf ? $conf->value : '';
}


if (!function_exists('getUnitsCount')) {
    function getUnitsCount($buildingId)
    {
        return Unit::where('building_id', $buildingId)->where('is_physical', 1)->count();
    }
}

if (!function_exists('getBuildingsByOwner')) {
    function getBuildingsByOwner($id = null, $type = null)
    {
        $buildings = \App\Models\Building::where('status', 1);
        if ($id) {
            $buildings = $buildings->where('user_id', $id);
        }
        if ($type) {
            $buildings = $buildings->where('type', $type);
        }

        return $buildings->get();
    }
}

function formatBytes($bytes, $precision = 2)
{

    if ($bytes) {
        $unit = [" B", " KB", " MB", " GB", " TB", " EB"];
        $exp = floor(log($bytes, 1024)) | 0;
        return round($bytes / (pow(1024, $exp)), $precision) . $unit[$exp];
    } else {
        return "0 B";
    }
}

function parseDate($request)
{
    $from = null;
    $to = null;
    if (isset($request->date_filter_value) && $request->date_filter_value != "" && isset($request->date_filter_radio) && $request->date_filter_radio == "date_range") {
        $datetime = explode("-", $request->date_filter_value);

        $dateStart = $datetime[0];

        $dateEnd = $datetime[1];
        $from = \Carbon\Carbon::parse(strtotime($dateStart))->subDay()->startOfDay()->format('Y-m-d H:i:s');
        $to = \Carbon\Carbon::parse(strtotime($dateEnd))->subDay()->endOfDay()->format('Y-m-d H:i:s');
    } else if (isset($request->date_filter_radio) && $request->date_filter_radio == "last_seven") {
        $from = \Carbon\Carbon::now()->subDays(7)->format('Y-m-d H:i:s');
    } else if (isset($request->date_filter_radio) && $request->date_filter_radio == "last_thirty") {
        $from = \Carbon\Carbon::now()->subDays(30)->format('Y-m-d H:i:s');
    } else if (isset($request->date_filter_radio) && $request->date_filter_radio == "last_quarter") {
        $from = \Carbon\Carbon::now()->subQuarter()->format('Y-m-d H:i:s');
    } else if (isset($request->date_filter_radio) && $request->date_filter_radio == "last_six") {
        $from = \Carbon\Carbon::now()->subMonths(6)->format('Y-m-d H:i:s');
    } else if (isset($request->date_filter_radio) && $request->date_filter_radio == "last_year") {
        $from = \Carbon\Carbon::now()->subYear()->format('Y-m-d H:i:s');
    } else if (isset($request->date_filter_radio) && $request->date_filter_radio == "year_to_date") {
        $from = \Carbon\Carbon::now()->startOfYear()->format('Y-m-d H:i:s');
    }
    if (!isset($to))
        $to = \Carbon\Carbon::now()->addDay()->format('Y-m-d H:i:s');

    return [
        "from" => $from,
        "to" => $to,
    ];
}

if (!function_exists('sendEmail')) {
    function sendEmail($msg, $to_email_address, $from_email_address = null, $subject = "My UrbanSky", $attachment = '', $useQueue = true)
    {
        addCommunicationLog("email", $to_email_address, $msg, $subject, $attachment);
        if (!$from_email_address) $from_email_address = config('app.MAIL_USERNAME');
        if (config('app.MAIL_TEST_TO')) {
            $msg = "Testing for $to_email_address\n" . $msg;
            $to_email_address = config('app.MAIL_TEST_TO');
        }

        try {
            $emailData = array(
                'to' => $to_email_address,
                'from' => $from_email_address,
                'subject' => $subject,
                'body' => $msg,
                'attachment' => $attachment
            );

            if (filter_var($to_email_address, FILTER_VALIDATE_EMAIL)) {
                if (!$useQueue) {
                    \Illuminate\Support\Facades\Mail::send('receiptmailscreen', ['body' => $emailData['body']], function ($m) use ($emailData) {
                        $m->from($emailData['from'], '');
                        $m->to($emailData['to'], '');
                        $m->subject($emailData['subject']);
                        if ($emailData['attachment'] && $emailData['attachment'] != "") {
                            if (is_array($emailData['attachment'])) {
                                foreach ($emailData['attachment'] as $datum) {
                                    $m->attach($datum);
                                }
                            } else
                                $m->attach($emailData['attachment']);
                        }
                    });
                } else
                    dispatch(new \App\Jobs\EmailSendJob($emailData));
            }
        } catch (\Exception $e) {
            dump($e);
            return false;
        }
        return true;
    }
}

if (!function_exists('addCommunicationLog')) {
    function addCommunicationLog($type, $to, $msg, $subject = "", $attachment = '', $from = null, $portal = null)
    {
        if ($type == "email") {
            $from = $from ?? config('app.MAIL_USERNAME');
            $user = User::where('email', $to)->first();
        }
        if ($type == "sms") {
            $from = $from ?? config('app.TWILIO_FROM');
            $user = User::where('mobile', $to)->first();
        }

        $notes = [
            'attachments' => [$attachment]
        ];
        DB::table('communications')->insert([
            'from' => $from,
            'user_id' => $user->id ?? null,
            'unit_id' => $user->unit->id ?? null,
            'building_id' => $user->unit->building->id ?? null,
            'type' => $type,
            'subject' => $subject,
            'body' => $msg,
            'details' => json_encode($notes),
            'created_at' => Carbon::now()->format("m/d/Y h:i:s a"),
            'updated_at' => Carbon::now()->format("m/d/Y h:i:s a"),
        ]);
    }
}

if (!function_exists('dde')) {
    function dde($msg, $folder = null)
    {
        if ($folder) {
            if (substr($folder, 0, 1) != '/') {
                $folder = storage_path('logs/') . $folder;
            }
        } else {
            $folder = storage_path('logs/debug.log');
        }
        error_log(date('Y-m-d H:i:s ') . print_r($msg, true) . "\n", 3, $folder);
    }
}


if (!function_exists('sendSMS')) {
    function sendSMS($msg, $mobile_no, $images = [])
    {
        try {
            $post = [
                'msg' => $msg,
                'mobile_no' => $mobile_no,
                'images' => $images,
            ];
            $response = sendURL(config('services.admin_url') . '/sendSms', $post);
            return true;

        } catch (Exception $e) {
            return false;
        }

    }
}

if (!function_exists('addSmsLog')) {
    function addSmsLog($to, $msg, $sid, $portal, $status, $user = null)
    {
        DB::table('sms_log')->insert([
            'user_id' => $user ? $user->id : null,
            'mobile' => $to,
            'type' => 'SMS',
            'is_incoming' => 1,
            'message' => $msg,
            'status' => $status,
            'sid' => $sid,
            'portal' => $portal,
            'timestamp' => Carbon::now(),
        ]);
    }
}

if (!function_exists('getAllBuildingsOfAUser')) {
    function getAllBuildingsOfAUser($userId = null)
    {
        if (!$userId)
            $userId = \Illuminate\Support\Facades\Auth::id();
        if (!$userId && \Illuminate\Support\Facades\Auth::user()->isSuperAdmin()) {
            return Building::all();
        }
        $userBuildings = DB::table('buildings_users')
            ->where('user_id', $userId)
            ->pluck('building_id')
            ->toArray();

        $buildings = Building::with('wallets')
            ->where(function ($query) use ($userBuildings) {
                $query->where('status', 1)
                    ->where('name', '!=', '')->whereIn('id', $userBuildings);
            })->get();


        return $buildings;
    }
}
if (!function_exists('isUserResident')) {
    function isUserResident($user)
    {
        return $user->usertype_id == 13 ? true : false;
    }
}

if (!function_exists('walletAccessNotification')) {
    function walletAccessNotification($currentTime, $user)
    {
        $msg = 'your Urban Sky e-wallet was accessed on ' . $currentTime . ' via ' . request()->ip() . ' and ' . getBrowser() . './n If this was you, no further action is needed./n  However, if not, then change your password immediately"';
        $subject = 'Wallet Access Notification';
        sendSMS($msg, $user->mobileno, []);
        // sendEmail($user, $subject, $msg, '');
        sendEmail($msg, $user->email, null, $subject, '', false);

    }
}

if (!function_exists('getBrowser')) {
    function getBrowser()
    {
        $u_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Chrome';
        if ($u_agent == "Chrome") return $u_agent;
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version = "";

        // First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        } elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }

        // Next get the name of the useragent yes seperately and for good reason
        if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        } elseif (preg_match('/Firefox/i', $u_agent)) {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        } elseif (preg_match('/Chrome/i', $u_agent)) {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        } elseif (preg_match('/Safari/i', $u_agent)) {
            $bname = 'Apple Safari';
            $ub = "Safari";
        } elseif (preg_match('/Opera/i', $u_agent)) {
            $bname = 'Opera';
            $ub = "Opera";
        } elseif (preg_match('/Netscape/i', $u_agent)) {
            $bname = 'Netscape';
            $ub = "Netscape";
        }

        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }

        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
                $version = $matches['version'][0];
            } else {
                $version = $matches['version'][1];
            }
        } else {
            $version = $matches['version'][0];
        }

        // check if we have a number
        if ($version == null || $version == "") {
            $version = "?";
        }

        return $bname . ' ' . $version . '(' . $platform . ')';
    }
}

if (!function_exists('sendOTPToUser')) {
    function sendOTPToUser($user_id)
    {
        $walletToken = new \App\Models\WalletToken();
        $autoGenerateOTP = $walletToken->autoGenerateOTP();

        if ($autoGenerateOTP) {

            $newWalletToken = $walletToken->insertNewWalletToken($autoGenerateOTP, $user_id);
            $user = User::find($user_id);
            $smsOTP = $autoGenerateOTP . ' is your wallet verification code.';

            if ($newWalletToken && $autoGenerateOTP && $user) {

                $msgSent = sendSMS($smsOTP, $user->mobile);

                if ($msgSent) {
                    return true;
                }
            }
            return false;
        }
    }
}
if (!function_exists('yourls')) {
    function yourls($url, $action = "shorturl", $format = 'simple')
    {
        switch ($format) {
            case 'json':
            case 'jsonp':
            case 'xml':
                break;
            default:
                $format = 'simple';
                break;
        }

        switch ($action) {
            case 'shorturl':
            case 'expand':
                $post['url'] = $url;
                $post['action'] = $action;
                if (config('app.YOURLS_SIGNATURE')) {
                    $post['signature'] = config('app.YOURS_SIGNATURE');
                } else {
                    $post['username'] = config('app.YOURLS_USERNAME');
                    $post['password'] = config('app.YOURLS_PASSWORD');
                }
                $post['format'] = $format;
                break;
            default:
                return null;
                break;
        }

        $url = config('app.YOURLS_SITE');
        return sendURL($url, $post);

    }
}

if (!function_exists('getPaymentLinkByToken')) {
    function getPaymentLinkByToken($token)
    {
        return yourls(config('app.ATTENDANT_URL') . "/pay/" . $token) == "" ? config('app.ATTENDANT_URL') . "/pay/" . $token : yourls(config('app.ATTENDANT_URL') . "/pay/" . $token);
    }
}

if (!function_exists('sendURL')) {
    function sendURL($url, $post = [], $timeout = 10, $creds = null)
    {
        $process = curl_init() or die ("Init Error");
        if (count($post) > 0) {
            $flds = http_build_query($post);
            curl_setopt($process, CURLOPT_POSTFIELDS, $flds);
            curl_setopt($process, CURLOPT_POST, count($post));
        } // If string then send as xml
        elseif (!empty($post)) {
            curl_setopt($process, CURLOPT_POSTFIELDS, $post);
            curl_setopt($process, CURLOPT_POST, 1);
            curl_setopt($process, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        }
        if (!empty($creds)) {
            list($user, $pass) = explode(':', $creds);
            curl_setopt($process, CURLOPT_USERPWD, "$user:$pass");
        }

        curl_setopt($process, CURLOPT_URL, $url);
        curl_setopt($process, CURLOPT_HEADER, 0);
        if ($timeout && $timeout < 1)
            curl_setopt($process, CURLOPT_TIMEOUT_MS, $timeout * 1000);
        else
            curl_setopt($process, CURLOPT_TIMEOUT, $timeout);

        curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($process, CURLOPT_SSL_VERIFYPEER, FALSE);
        $return = curl_exec($process) or curl_error($process);

        return $return;
    }
}
if (!function_exists('getOwnerUnit')) {
    function getOwnerUnit($userId, $buildingId)
    {
        $unitsUsers = DB::table('units_users')->where('user_id', $userId)->get();

        foreach ($unitsUsers as $unitsUser) {
            $unit = Unit::find($unitsUser->unit_id);

            if ($unit->unittype_id == 12 && $unit->building_id == $buildingId) {
                return $unit->id;
            }
        }

        return null;
    }
}
if (!function_exists('addNewLinkable')) {
    function addNewLinkable($userId, $unitId, $type, $expiryDays = null, $transactionLogs = [], $walletId = null, $id = null, $cartType = null, $buildingId = null)
    {
        $item = new \App\Models\LinkAbles();

        if ($id) {
            $item->id = $id;
        }
        if ($userId) {
            $item->linkable_id = $userId;
            $item->linkable_type = \App\User::class;
            $item->unit_id = $unitId;
            $item->building_id = $buildingId;
            $item->token = \Illuminate\Support\Str::random(12);
            $item->wallet_id = $walletId;
            $item->cart_type = $cartType;
            $item->type = $type;
            $item->status = 'pending';
            if (isset($expiryDays))
                $item->expires_at = Carbon::now()->addDays($expiryDays);
            $item->created_at = Carbon::now();
            $item->save();

            foreach ($transactionLogs as $transactionLog) {
                $transactionLog->linkableitems()->create([
                    'linkables_id' => $item->id,
                    'status' => 0,
                ]);
            }
        }

        return $item;
    }
}

if (!function_exists('createTask')) {
    function createTask($taskTemplateId, $name, $userId, $unitId, $buildingId, $daysActive, $priority, $subjectType, $subjectId, $targetType, $targetId, $jsonData, $createdBy, $rpJsonData = null)
    {
        $expiry = \Carbon\Carbon::now()->addDays($daysActive);

        $data['task_template_id'] = $taskTemplateId;
        $data['name'] = $name;

        $data['user_id'] = $userId;
        $data['unit_id'] = $unitId;
        $data['building_id'] = $buildingId;
        $data['expiry'] = $expiry;

        $data['priorities'] = $priority;
        $data['target_type'] = $targetType;
        $data['target_id'] = $targetId;
        $data['subject_type'] = $subjectType;
        $data['subject_id'] = $subjectId;
        $data['status'] = '1';
        $data['json_message'] = json_encode($jsonData);
        $data['rp_json_message'] = $rpJsonData == null ? json_encode($jsonData) : json_encode($rpJsonData);
        $data['created'] = date('Y-m-d h:i:s');
        $data['modified'] = date('Y-m-d h:i:s');
        $data['created_by'] = $createdBy;
        $taskData = Tasks::create($data);

        return $taskData;
    }
}

if (!function_exists('sendOneTimeInvoice')) {
    function sendOneTimeInvoice($user, $building, $transactions, $link, $title, $cartType, $senderUser, $linkable = null, $path = null)
    {
        $colors = [
            'additional_rent' => '#2cb1dc',
            'rent' => '#2cb1dc',
            'amenities' => '#80bc45',
            'laundry' => '#484848',
        ];

        $data['invoiceTitle'] = "One Time Invoice";
        $data['invoiceSubTitle'] = "";
        $data['senderUser'] = $senderUser;
        $data['transactions'] = $transactions;
        $data['buildingId'] = $building->id;
        $data['buildingName'] = $building->address;
        $data['user'] = $user;
        $data['title'] = $title;
        $data['linkableId'] = $linkable->linkable_id;
        $data['link'] = $link;
        $data['token'] = $linkable->token;
        $data['cartType'] = TransactionLog::$transToCart[$cartType];
        $data['color'] = $colors[TransactionLog::$transToCart[$cartType]];
        $total = array_sum(array_column($transactions, 'amount'));

        $pdf = PDF::loadView('invoice.resident', $data);
        $strtotime = strtotime(now());
        $filePath = 'app/invoice-' . $strtotime . '.pdf';
        $pdf->save(storage_path($filePath));

        $smsBody = "Hi $user->firstname $user->lastname, \nUrban Sky Invoice \nYour Amount Due: $" . chargeOrPaymanetAmountFormatter($total);

        $smsBody = "You received a convenience Payment link from Urban Sky.  Click $link for details.";

        sendEmail($title, $user->email, config('mail.mailers.smtp.username'), "Urban Sky One Time Invoice", storage_path($filePath));
        sendSMS($smsBody, $user->mobile);
        addUnitLogs(Null, $building->id, 21, 'One Time Invoice', $title, $user->id);
    }
}
if (!function_exists('chargeOrPaymanetAmountFormatter')) {
    function chargeOrPaymanetAmountFormatter($amount)
    {
        if ($amount < 0)
            return chargeAmountFormatter($amount);
        else
            return paymentAmountFormatter($amount);
    }
}
if (!function_exists('chargeAmountFormatter')) {
    function chargeAmountFormatter($amount)
    {
        return "$(" . number_format(abs($amount), 2) . ")";
    }
}
if (!function_exists('paymentAmountFormatter')) {
    function paymentAmountFormatter($amount)
    {
        return "$" . number_format(abs($amount), 2) . "";
    }
}

if (!function_exists('addUnitLogs')) {
    function addUnitLogs($unitId, $buildingId, $entityName, $actionName, $notes, $triggeredBy = null, $entity_id = 0)
    {
        if (is_array($notes)) $notes = json_encode($notes);

        DB::table('unit_logs')->insert(
            [
                'entity_id' => $entity_id,
                'unit_id' => isset($unitId) ? $unitId : null,
                'building_id' => isset($buildingId) ? $buildingId : null,
                'entity_name' => isset($entityName) ? $entityName : null,
                'action_name' => isset($actionName) ? $actionName : null,
                'triggered_at' => date('Y-m-d H:i:s'),
                'triggered_by' => $triggeredBy ?? 0,
                'notes' => $notes,
                'origin_from_task' => 0,
                'ip_address' => request()->ip(),
                'user_agent' => getBrowser(),
                'initiated_by' => $triggeredBy
            ]
        );
    }
}

if (!function_exists('addUnitsLog')) {
    function addUnitsLog($entity_id, $entity_name, $action_name, $notes = null, $building_id = null, $unit_id = null, $task = 0, $user_id = null, $triggeredBy = null)
    {

        if (is_array($notes) || is_object($notes)) {
            $notes = json_encode($notes); // encode notes
        }

        $unitLog = new \App\Models\UnitLogs();

        $unitLog->entity_id = $entity_id;
        $unitLog->unit_id = $unit_id;
        $unitLog->building_id = $building_id;
        $unitLog->entity_name = $entity_name;
        $unitLog->action_name = $action_name;
        $unitLog->triggered_at = date('Y-m-d H:i:s');
        $unitLog->triggered_by = $triggeredBy ?? (\Illuminate\Support\Facades\Auth::user()->id ?? 0);
        $unitLog->notes = $notes;
        $unitLog->origin_from_task = $task;
        $unitLog->ip_address = request()->ip();
        $unitLog->user_agent = getBrowser();


        if (!is_null($user_id)) {
            $unitLog->initiated_by = $user_id;
        }

        $unitLog->save();

    }
}
if (!function_exists('updateLandlordBalance')) {
    function updateLandlordBalance($amount, $userId, $buildingId, $transSourceId)
    {
        $array = ['building_id' => $buildingId, 'landlord_id' => $userId, 'trans_source_id' => $transSourceId];
        $landLordBalance = \App\Models\LandLordBalance::where($array)->first();
        if (!$landLordBalance) {
            $landLordBalance = \App\Models\LandLordBalance::create($array);
        }

        $balance = $landLordBalance->payable_balance;
        $landLordBalance->payable_balance = $balance + $amount;
        $landLordBalance->save();
    }
}

if (!function_exists('getUserTypesForReceviable')) {
    function getUserTypesForReceviable()
    {
        $userTypeData = UserTypes::where('receivable', 1)->orderBy('name')->get();
        foreach ($userTypeData as $i => $data) {
            if ($data->name == 'Landlord') $userTypeData[$i]->name = 'Owner';
        }

        return $userTypeData;
    }
}
if (!function_exists('getUserTypesForPayable')) {
    function getUserTypesForPayable()
    {
        $userTypeData = UserTypes::where('payable', 1)->orderBy('name')->get();

        foreach ($userTypeData as $i => $data) {
            if ($data->name == 'Landlord') $userTypeData[$i]->name = 'Owner';
        }

        return $userTypeData;
    }
}

if (!function_exists('addTokenHistory')) {
    function addTokenHistory($token, $building_id, $type)
    {
        $fob_text = ($token['mfob_status'] == 0) ? 'FOB ' : 'mFOB ';

        $TokenHistory['token_id'] = $token['id'];
        $TokenHistory['building_id'] = $building_id;
        $TokenHistory['user_id'] = $token['user_history'];
        $TokenHistory['action_date'] = date("Y-m-d H:i:s");

        $TokenHistory['action'] = $fob_text . $token['card_id'] . ' Recycled';
        $TokenHistory['action_type'] = $type;
        $TokenHistory['unit_id'] = null;
        $TokenHistory['action_by'] = Auth::id();

        TokenHistory::create($TokenHistory);
    }
}

if (!function_exists('createWalletTask')) {
    function createWalletTask($user_id, $unit_id, $building_id = null)
    {
        $task = Tasks::where('target_id', $unit_id)->where('task_template_id', 12)->where('task_state', 'O')->count();


        $expiry = date("Y-m-d", strtotime(date('m', strtotime('+1 month')) . '/01/' . date('Y') . ' 00:00:00'));
        if (!$task) {
            $user = User::find($user_id);
            $link = rtrim(config('app.ATTENDANT_URL'), '/') . '/wallet/create/' . $user_id . '/p/s';

            $json_data['name'] = ucfirst($user['firstname']) . ' ' . $user['lastname'];
            $json_data['link'] = "<a href='" . $link . "'>here</a>";
            $json_data['raw_link'] = rtrim(config('app.ATTENDANT_URL'), '/') . '/wallets/add_mywallet/pay';

            $dataguaranter['task_template_id'] = 12;
            $dataguaranter['name'] = 'Registering your E-Wallet';
            $dataguaranter['priorities'] = '3';

            $dataguaranter['user_id'] = $user_id;
            $dataguaranter['unit_id'] = $unit_id;
            $dataguaranter['building_id'] = $building_id;
            $dataguaranter['expiry'] = $expiry;

            $dataguaranter['target_type'] = 'User';
            $dataguaranter['target_id'] = $user_id;
            $dataguaranter['status'] = '1';
            $dataguaranter['rp_json_message'] = json_encode($json_data);
            $json_data['raw_link'] = rtrim(config('app.portal_url'), '/') . '/wallets/add_mywallet/pay';
            $dataguaranter['json_message'] = json_encode($json_data);
            $data['created'] = date('Y-m-d h:i:s');
            $data['modified'] = date('Y-m-d h:i:s');
            $data['created_by'] = Auth::id();

            Tasks::create($data);
        }
    }
}

if (!function_exists('getAllSchedules')) {
    function getAllSchedules($type = null)
    {
        return Schedule::
        when(Auth::user() && !Auth::user()->isSuperAdmin(), function ($q) {
            $q->where('owner_id', Auth::id());
        })
            ->when(!is_null($type), function ($q) use ($type) {
                $q->where('type', $type);
            })
            ->get();
    }
}
if (!function_exists('updateScheduleInBrivo')) {
    function updateScheduleInBrivo($brivo_schedule_id, $schedule)
    {

        $schedule->load('exceptions.exception');
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $schedule_periods = json_decode($schedule->schedule_periods, true);

        $schedule_blocks = [];
        foreach ($schedule_periods as $period) {
            foreach ($period['periods'] as $key => $slots) {
                if (isset($slots['start']) && isset($slots['end'])) {
                    $end = $slots['end'];
                    if ($end == '00:00') $end = '23:59';
                    $schedule_blocks[$days[$period['day']]][$key]['start'] = $slots['start'];
                    $schedule_blocks[$days[$period['day']]][$key]['end'] = $slots['end'];
                }
            }
        }
        if (count($schedule_blocks) == 0) return null; //Only one schedule block is must for creating a schedule in brivo
        $posts = [
            'name' => $schedule->name,
            'scheduleBlocks' => $schedule_blocks
        ];
        if (!is_null($schedule->building_id) && $schedule->building_id > 0) {
            $building = Building::find($schedule->building_id);
            if (isset($building) && !is_null($building->brivo_id)) $posts['siteId'] = $building->brivo_id;
        }
        $posts['scheduleExceptions'] = prepareExceptionsDataForBrivo($schedule);
        // dd($posts);
        dde($posts, 'brivo.log');
        $brivo = new BrivoApi();
        $response = $brivo->putSection('schedules/' . $brivo_schedule_id, $posts);
        return $response;
    }
}

if (!function_exists('prepareExceptionsDataForBrivo')) {
    function prepareExceptionsDataForBrivo($schedule)
    {
        $exception_blocks = [];
        $start = '00:00';
        $ends = '23:59';
        if (isset($schedule)) {
            $exception_dates = getExceptionDates($schedule->id);
            foreach ($exception_dates as $e) {
                if (count($exception_blocks) > 16) break;
                $exception_blocks[] = [
                    "enabling" => "DISABLE",
                    "start" => $start,
                    "end" => $ends,
                    "recurrence" => [
                        "type" => "SINGULAR",
                        "date" => $e
                    ]
                ];
            }

        }
        return $exception_blocks;
    }
}

if (!function_exists('getExceptionDates')) {
    function getExceptionDates($schedule_id = null, $verbose = false)
    {
        $days = [
            '1' => 'Sunday',
            '2' => 'Monday',
            '3' => 'Tuesday',
            '4' => 'Wednesday',
            '5' => 'Thursday',
            '6' => 'Friday',
            '7' => 'Saturday',
        ];
        $nums = [
            'first' => 0,
            'second' => 1,
            'third' => 2,
            'fourth' => 3,
            'fifth' => 4,
        ];
        $dates = $list = [];
        if ($schedule_id) {
            $rows = ScheduleException::where('schedule_id', $schedule_id)
                ->join('exceptions as e', 'schedule_exceptions.exception_id', '=', 'e.id')
                ->get();

            if ($rows) {
                $today = date('Y-m-d');
                foreach ($rows as $row) {

                    $time = new Carbon($row->start_date);
                    if ($verbose) echo "$row->schedule_id $row->name $today\n" . print_r($time, true) . "\n";
                    switch ($row->repeat_type) {
                        case 'day':
                            switch ($row->ends_on_type) {
                                case 'end_date_time':
                                    $edt = date('Y-m-d', strtotime($row->ends_on_type_val));
                                    if ($verbose) echo "$edt - " . $time->toDateString() . "\n";
                                    while ($time->toDateString() <= $edt) {

                                        if ($time->toDatestring() >= $today)
                                            $dates[$time->toDateString()] = 1;
                                        $time->addDays($row->repeats_every);
                                    }
                                    break;
                                case 'end_times':
                                    for ($i = 0; $i < $row->ends_on_type_val; $i++) {
                                        $dates[$time->toDateString()] = 1;
                                        $time->addDays($row->repeats_every);
                                    }
                                    break;
                            }
                            break;
                        case 'week':
                            if ($verbose) echo "$row->repeat_type_week_on\n";
                            foreach (json_decode($row->repeat_type_week_on, true) as $day) {

                                $time = new Carbon($row->start_date . ' next ' . $days[$day]);


                                if ($verbose) echo "$row->schedule_id $row->name $today\n" . print_r($time, true) . "\n";

                                switch ($row->ends_on_type) {
                                    case 'end_date_time':
                                        $edt = date('Y-m-d', strtotime($row->ends_on_type_val));
                                        while ($time->toDateString() <= $edt) {
                                            if ($time->toDatestring() >= $today)
                                                $dates[$time->toDateString()] = 1;
                                            $time->addWeeks($row->repeats_every);
                                        }
                                        break;
                                    case 'end_times':
                                        for ($i = 0; $i < $row->ends_on_type_val; $i++) {
                                            if ($time->toDatestring() >= $today)
                                                $dates[$time->toDateString()] = 1;
                                            $time->addWeeks($row->repeats_every);
                                        }
                                        break;
                                }
                            }
                            break;
                        case 'month':
                            if ($verbose) echo "month: $row->repeat_type_month_val\n";
                            if (strpos($row->repeat_type_month_val, 'occurence') !== false) {
                                if ($verbose) echo "occurance-type\n";

                                $d = explode(' ', substr($row->repeat_type_month_val, 10));
                                if (!isset($d[1]))
                                    break;

                                $num = $nums[$d[0]];
                                if ($verbose) echo substr($row->repeat_type_month_val, 10) . ": $d[0] $d[1] $num\n";
                                $dt = strtotime(date('Y-m-01', strtotime($row->start_date)) . " first $d[1]");
                                if ($verbose) echo "First $d[1] " . date('Y-m-d', $dt) . "\n";

                                $dt += 604800 * $num;
                                if ($verbose) echo "$d[0] $d[1] " . date('Y-m-d', $dt) . "\n";
                                $time = new Carbon(date('Y-m-d', $dt));

                                if ($verbose) echo "$row->schedule_id $row->name $today $num $d[0] $d[1] \n" . print_r($time, true) . "\n";

                                switch ($row->ends_on_type) {
                                    case 'end_date_time':
                                        $edt = date('Y-m-d', strtotime($row->ends_on_type_val));
                                        while ($time->toDateString() <= $edt) {
                                            if ($verbose) echo "month: " . $time->toDateString() . "\n";
                                            if ($time->toDatestring() >= $today)
                                                $dates[$time->toDateString()] = 1;

                                            $time->addMonths($row->repeats_every);

                                            $num = $nums[$d[0]];
                                            $dt = strtotime(date('Y-m-01', strtotime($time->toDateString())) . " first $d[1]");
                                            $dt += 604800 * $num;
                                            $time = new Carbon(date('Y-m-d', $dt));
                                            if ($verbose) print_r($time);

                                        }
                                        break;
                                    case 'end_times':
                                        for ($i = 0; $i < $row->ends_on_type_val; $i++) {
                                            if ($time->toDatestring() >= $today)
                                                $dates[$time->toDateString()] = 1;
                                            $time->addMonths($row->repeats_every);

                                            $num = $nums[$d[0]];
                                            $dt = strtotime(date('Y-m-01', strtotime($time->toDateString())) . " first $d[1]");
                                            $dt += 604800 * $num;
                                            $time = new Carbon(date('Y-m-d', $dt));
                                            if ($verbose) print_r($time);
                                        }
                                        break;
                                }
                            } elseif (strpos($row->repeat_type_month_val, 'date') !== false) {
                                if ($verbose) echo "date-type\n";
                                $d = substr($row->repeat_type_month_val, 5);

                                $time = new Carbon($row->start_date);

                                if ($verbose) echo "$row->schedule_id $row->name $d \n" . print_r($time, true) . "\n";

                                switch ($row->ends_on_type) {
                                    case 'end_date_time':
                                        $edt = date('Y-m-d', strtotime($row->ends_on_type_val));
                                        while ($time->toDateString() <= $edt) {
                                            if ($verbose) echo "month: " . $time->toDateString() . "\n";
                                            if ($time->toDatestring() >= $today)
                                                $dates[$time->toDateString()] = 1;

                                            $time->addMonths($row->repeats_every);
                                        }
                                        break;
                                    case 'end_times':
                                        for ($i = 0; $i < $row->ends_on_type_val; $i++) {
                                            if ($time->toDatestring() >= $today)
                                                $dates[$time->toDateString()] = 1;

                                            $time->addMonths($row->repeats_every);
                                        }
                                        break;
                                }
                            } else {
                                if ($verbose) echo "No match to $row->repeat_type_month_val\n";
                            }
                            break;
                        case 'year':
                            if ($verbose) echo "$row->schedule_id $row->name $today\n" . print_r($time, true) . "\n";

                            $time = new Carbon($row->start_date);
                            switch ($row->ends_on_type) {
                                case 'end_date_time':
                                    $edt = date('Y-m-d', strtotime($row->ends_on_type_val));
                                    while ($time->toDateString() <= $edt) {
                                        if ($time->toDatestring() >= $today)
                                            $dates[$time->toDateString()] = 1;
                                        $time->addYears($row->repeats_every);
                                    }
                                    break;
                                case 'end_times':
                                    for ($i = 0; $i < $row->ends_on_type_val; $i++) {
                                        if ($time->toDatestring() >= $today)
                                            $dates[$time->toDateString()] = 1;
                                        $time->addYears($row->repeats_every);
                                    }
                                    break;
                            }
                            break;
                    }
                }
                ksort($dates);
                $list = [];
                $i = 0;
                foreach ($dates as $dt => $v) {
                    if ($i++ >= 16)
                        break;
                    $list[] = $dt;
                }
            }
        }
        return $list;
    }
}

if (!function_exists('update_user_unit_history')) {
    function update_user_unit_history($unit_id, $user_id)
    {

        $data['move_out_time'] = "'" . date('Y-m-d H:i:s') . "'";
        $data['status'] = 0;
        return UnitHistory::where('unit_id', $unit_id)->where('user_id', $user_id)->where('status', 1)->update($data);
    }
}

if (!function_exists('containsInteger')) {
    function containsInteger($arr)
    {
        foreach ($arr as $element) {
            if (is_int($element)) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('containsOnlyNull')) {
    function containsOnlyNull($arr)
    {
        foreach ($arr as $element) {
            if ($element !== null) {
                return false;
            }
        }
        return true;
    }
}

if (!function_exists('getLinkedBuildings')) {

    function getLinkedBuildings($buildingId, $inArray = false)
    {
        $lBuildings = LinkedBuildings::with('linkedBuilding')->where('building_id', $buildingId)->get();
        if ($inArray) {
            $lBuildings = $lBuildings->pluck('linked_building_id')->toArray();
        }

        return $lBuildings;
    }
}


if (!function_exists('getBuildingDetails')) {

    function getBuildingDetails($buildingId = null)
    {
        if (!$buildingId)
            $buildingId = \Illuminate\Support\Facades\Session::get('building_id');
        if ($buildingId)
            return Building::find($buildingId);

        return null;
    }
}


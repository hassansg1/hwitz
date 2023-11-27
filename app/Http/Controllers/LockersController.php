<?php

namespace App\Http\Controllers;


use App\Models\ExtensionLog;
use App\Models\Building;
use App\Models\LockerPackage;
use App\Services\Parsers\LockersParser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class LockersController extends Controller
{

    public function index(Request $request)
    {
        if (!checkIfBuildingBelongsToOwner($request->buildingId)) {
            return response()->json(
                [
                    "status" => false,
                    "message" => "Unauthorized action.",
                ]
            );
        }

        $data = app(LockersParser::class)->parse($request);

        return response()->json(
            [
                "status" => true,
                "data" => $data,
            ]
        );
    }

    public function openLocker(Request $request)
    {
        $lockerPackageId = $request->packageId;
        $buildingId = $request->buildingId;
        $keepOccupied = $request->keepOccupied;

        $url = config('app.ATTENDANT_URL');

        $post['receiverId'] = Auth::id();
        $post['token'] = Crypt::encryptString(Auth::user()->email);
        $post['lockerPackageId'] = $lockerPackageId;
        $post['buildingId'] = $buildingId;
        $post['keepOccupied'] = $keepOccupied;
        $post['from'] = 'Owner Portal';

        $response = sendURL($url . '/api/package/unlock', $post);

        return response()->json(
            [
                "status" => true,
                "icon" => "success",
                "message" => $keepOccupied ? "Success, Locker Opened.." : "Success, Status is changed..",
            ]
        );
    }

    public function lockerPackageExtension(Request $request)
    {
        $packageId = $request->packageId;

        $packageData = LockerPackage::where('id', $packageId)->first();
        if (!$packageData) {
            return response()->json([
                "status" => false,
                "icon" => "error",
                "message" => "Package not found.",
            ]);
        }
        try {
            $now = Carbon::now();
            $expiration_time = $packageData->expiration_time;
            $extended_time = Carbon::parse($expiration_time)->addHours($request->hours ?? 24);
            $updatedTime = $extended_time > $now ? ['expiration_time' => $extended_time, 'is_locked' => 0] : ['expiration_time' => $extended_time];
            $building = Building::find($packageData->building_id);

            LockerPackage::where('id', $packageData->id)->update($updatedTime);
            ExtensionLog::addNew($packageData->id, $expiration_time, $extended_time, $packageData->locker_id, Auth::user()->id);
            $notes = ['locker_package_id' => $packageData->id];
            addUnitsLog(0, 23, 'Extension Granted For 1 Day by Manager', $notes, $packageData->building_id, $packageData->receiver->unit->id ?? null, 0, $building->user_id, $building->user_id);
            $email = $packageData->receiver->email;
            $mobile = $packageData->receiver->mobile;
            $time = date('m/d/Y', strtotime($extended_time)) . " Midnight";
            $message = 'Your package extension request was approved.  Please Pick up by ' . $time;
            sendEmail($message, $email, config('app.MAIL_USERNAME'), 'Expiration Extension', '');
            sendSMS($message, $mobile);

            return response()->json([
                "status" => true,
                "icon" => "success",
                "message" => "1 day extension granted.",
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                "status" => true,
                "icon" => "error",
                "message" => $exception->getMessage(),
            ]);
        }
    }

    public function viewExtensionHistory(Request $request)
    {
        $history = ExtensionLog::with('user', 'user.usertype')->where('locker_package_id', $request->packageId)->get();

        return response()->json([
            "status" => true,
            "icon" => "success",
            "data" => $history,
        ]);
    }

    public function sendPackageAlert(Request $request)
    {
        $lockerPackage = LockerPackage::where('id', $request->packageId)->first();

        $receiver = $lockerPackage->receiver;
        $locker_name = $lockerPackage->locker->label ?? '';

        $lastPickUpTme = Carbon::parse($lockerPackage->expiration_time);
        $pickUpTimeString = $lastPickUpTme->format('m/d/Y') . " Midnight";

        $message = $receiver->initials . ",You've got a package:<br>" . ($lockerPackage->locker->lockerScreen->name ?? '')
            . ":$locker_name<br>Pick up by $pickUpTimeString<br>";

        $forSms = $receiver->initials . ",You've got a package:\n" . ($lockerPackage->locker->lockerScreen->name ?? '')
            . ":$locker_name\nPick up by $pickUpTimeString\n";

        if ($request->emailAlert)
            sendEmail($message, $receiver->email, config('app.MAIL_USERNAME'), "Package Reminder for $receiver->name");
        if ($request->textAlert)
            sendSMS($forSms, $receiver->mobile);

        return response()->json([
            "status" => true,
            "icon" => "success",
            "message" => "Package alert successfully sent to resident.",
        ]);
    }
}
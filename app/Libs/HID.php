<?php

namespace App\Libs;

use App\Libs\BrivoApi;
use App\Models\Asset;

class HID {

    public $username;
    public $password;
    public $db = null;
    public $data = [];
    public $verbose = false;
    public $fac = 2814;
    public $bits = 37;

    public $tzlist = array(
    'EDT' => 'EST6EDT,M3.2.0/2,M11.1.0/2',
    'EST' => 'EST6EDT,M3.2.0/2,M11.1.0/2',
    'CDT' => 'CST6CDT,M3.2.0/2,M11.1.0/2',
    'CST' => 'CST6CDT,M3.2.0/2,M11.1.0/2',
    'MST' => 'MST',
    'MDT' => 'MST7MDT,M3.2.0/2,M11.1.0/2',
    'MDT' => 'MST7MDT,M3.2.0/2,M11.1.0/2',
    'PDT' => 'PST8PDT,M3.2.0/2,M11.1.0/2',
    'PST' => 'PST8PDT,M3.2.0/2,M11.1.0/2',
    'GMT' => 'GMT0',
    );

    public $model_id;
    public $brivo_id;


    public $xml1 = '<?xml version="1.0" encoding="UTF-8"?><VertXMessage>';
    public $xml2 = '</VertXMessage>';


    public function connect() {
       // include app_path().'/libs/DataBase.php';
        //$dbc = new DATABASE_CONFIG;

        $this->db = mysqli_connect(config('database.mysql.host'),
            config('database.mysql.username'),
            config('database.mysql.password'),
            config('database.mysql.database')) or die("Unable to connect to DB");

        $this->db->query("SET NAMES utf8");

    }

    public function sql_query($query) {
        if (!$this->db)
            $this->connect();

        $r = $this->db->query($query) or die("Error in $query");

        return $r;
    }

    public function getLines($url, $post = '', $timeout = 60) {
        $process = curl_init() or die ("Init Error");
        if ($post)  {
            curl_setopt($process, CURLOPT_POSTFIELDS, $post);
        }

        curl_setopt($process, CURLOPT_USERPWD, "$this->username:$this->password");
        curl_setopt($process, CURLOPT_URL, $url);
        curl_setopt($process, CURLOPT_HEADER, 0);
        curl_setopt($process, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);

        $return = curl_exec($process) or curl_error($process);

        curl_close($process);
        // If there is no return switch to https if not already using.
        if (empty($return) && strpos($url, 'https:') === false) {
            $url = str_replace('http:','https:', $url);
            $process = curl_init() or die ("Init Error");
            if ($post)  {
                curl_setopt($process, CURLOPT_POSTFIELDS, $post);
            }

            curl_setopt($process, CURLOPT_USERPWD, "$this->username:$this->password");
            curl_setopt($process, CURLOPT_URL, $url);
            curl_setopt($process, CURLOPT_HEADER, 0);
            curl_setopt($process, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);

            $return = curl_exec($process) or curl_error($process);
            curl_close($process);

        }

        // If unauthorized switch to digest
        if (strpos($return, '401 - Unauthorized') !== false) {
            $process = curl_init() or die ("Init Error");
            if ($post)  {
                curl_setopt($process, CURLOPT_POSTFIELDS, $post);
            }

            curl_setopt($process, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
            curl_setopt($process, CURLOPT_USERPWD, "$this->username:$this->password");
            curl_setopt($process, CURLOPT_URL, $url);
            curl_setopt($process, CURLOPT_HEADER, 0);
            curl_setopt($process, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);

            $return = curl_exec($process) or curl_error($process);
            curl_close($process);
        }

        $lines = str_replace(">",">\n", $return);

        return $lines;
    }

    // URL 
    public function URL($url, $post = null, $timeout = 30) {
        $process = curl_init() or die ("Init Error");
        if ($post)  {
            curl_setopt($process, CURLOPT_POSTFIELDS, $post);
        }

        if ($this->username) {
            curl_setopt($process, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
            curl_setopt($process, CURLOPT_USERPWD, "$this->username:$this->password");
        }
        curl_setopt($process, CURLOPT_URL, $url);
        curl_setopt($process, CURLOPT_HEADER, 0);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($process, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($process, CURLOPT_SSL_VERIFYSTATUS, 0);
        curl_setopt($process, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($process, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
        /*
        curl_setopt($process, CURLOPT_VERBOSE, true);
        curl_setopt($process, CURLOPT_STDERR, fopen(LOGS.'/curl.log', 'a+'));
         */

        $return = curl_exec($process) or trigger_error(curl_error($process));
        $httpcode = curl_getinfo($process, CURLINFO_RESPONSE_CODE);

        dde("URL: $url $this->username $httpcode $return", 'hid.log');

        curl_close($process);
        return $return;
    }

    // Parse XML
    public function xmlParse($lines) {
        $parser = xml_parser_create('');
        xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8");
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, trim($lines), $xml_values);
        xml_parser_free($parser);

        $out = array();
        foreach ($xml_values as $k => $v)
            if ($v['type'] == 'complete')
                $out[] = $v;


        return $out;
    }

    public function hidGet($xml = '') {

        $xml = urlencode($this->xml1.$xml.$this->xml2);
        $url = "http://$this->host/cgi-bin/vertx_xml.cgi?XML=$xml";
        return $this->getLines($url);
    }

    public function hidAddCard($cid) {
        $xml = '<hid:Credentials action="AD">
        <hid:Credential cardNumber="'.$cid.'" isCard="true" endTime=""  />
        </hid:Credentials>
        ';

        $rtn = $this->hidGet($xml);


        return $this->xmlParse($rtn);
    }

    public function hidListCards($recs = 1000, $expand = false) {
        $expand = $expand === true ? 'responseFormat="expanded" ' : '';
        $xml = '<hid:Credentials action="LR" '.$expand.'recordOffset="0" recordCount="'.$recs.'"/>';

        return $this->xmlParse($this->hidGet($xml));
    }

    public function hidAssignCard($pid, $cid) {
        $xml = '<hid:Credentials action="UD" rawCardNumber="'.$cid.'" isCard="true">
        <hid:Credential cardholderID="'.$pid.'"/>
        </hid:Credentials>
        ';

        return $this->xmlParse($this->hidGet($xml));
    }

    public function hidAssignSchedule($pid, $sid) {
        $xml = '<hid:RoleSet action="UD" roleSetID="'.$pid.'">
        <hid:Roles><hid:Role roleID="'.$pid.'" scheduleID="'.$sid.'" resourceID="0"/>
        </hid:Roles></hid:RoleSet>
        ';

        return $this->xmlParse($this->hidGet($xml));
    }


    public function hidAddPerson($data) {
        $fname = empty($data['fname']) ? '' : $data['fname'];
        $mname = empty($data['mname']) ? '' : $data['mname'];
        $lname = empty($data['lname']) ? '' : $data['lname'];
        $email = empty($data['email']) ? '' : $data['email'];
        $phone = empty($data['phone']) ? '' : $data['phone'];

        $d['forename'] = $fname;
        $d['middleName'] = $mname;
        $d['surname'] = $lname;
        $d['exemptFromPassback'] = 'true';
        $d['extendedAccess'] = 'false';
        $d['confirmingPin'] = '';
        $d['email'] = $email;
        $d['custom1'] = '';
        $d['custom2'] = '';
        $d['phone'] = $phone;

        $xml = '<hid:Cardholders action="AD"><hid:Cardholder ';
        foreach ($d as $k => $v)
            $xml .= "$k=\"$v\" ";
        $xml .= ' /></hid:Cardholders>';

        return $this->xmlParse($this->hidGet($xml));
    }

    public function hidEditPerson($pid, $data) {
        $fname = empty($data['fname']) ? '' : $data['fname'];
        $mname = empty($data['mname']) ? '' : $data['mname'];
        $lname = empty($data['lname']) ? '' : $data['lname'];
        $email = empty($data['email']) ? '' : $data['email'];
        $phone = empty($data['phone']) ? '' : $data['phone'];

        $d['forename'] = $fname;
        $d['middleName'] = $mname;
        $d['surname'] = $lname;
        $d['RoleSetID'] = $pid;
        $d['exemptFromPassback'] = 'true';
        $d['extendedAccess'] = 'false';
        $d['confirmingPin'] = '';
        $d['email'] = $email;
        $d['custom1'] = '';
        $d['custom2'] = '';
        $d['phone'] = $phone;

        $xml = '<hid:Cardholders action="UD" cardholderID="'.$pid.'\"><hid:Cardholder ';
        foreach ($d as $k => $v)
                $xml .= "$k=\"$v\" ";
        $xml .= ' /></hid:Cardholders>';

        return $this->xmlParse($this->hidGet($xml));
    }

    public function hidListPeople($recs = 1000, $expand = false) {
        $expand = $expand === true ? 'responseFormat="expanded" ' : '';
        $xml = '<hid:Cardholders action="LR" '.$expand.'recordOffset="0" recordCount="'.$recs.'"/>';

        return $this->xmlParse($this->hidGet($xml));
    }

    public function hidEventNum() {
        $xml = '<hid:EventMessages action="DR" />';
            $rtn = $this->xmlParse($this->hidGet($xml));
            if (array_key_exists(0, $rtn) && array_key_exists('attributes', $rtn[0]))
                $var = $rtn[0]['attributes'];
            else
                $var = array();
            $num = array_key_exists('eventsInUse', $var) ? $var['eventsInUse'] : 0;
        return $num;
    }

    public function hidListEvents($recs = 30, $offset = 0, $expand = false) {
        $expand = $expand === true ? 'responseFormat="expanded" ' : '';
        $xml = '<hid:EventMessages action="LR" '.$expand.'recordOffset="'.$offset.'" recordCount="'.$recs.'"/>';

        return $this->xmlParse($this->hidGet($xml));
    }

    public function hidListSchedules($rec = 10, $expand = false) {
        $expand = $expand === true ? 'responseFormat="expanded" ' : '';
        $xml = '<hid:Schedules action="LR" '.$expand.'recordOffset="0" recordCount="'.$recs.'"/>';

        return $this->xmlParse($this->hidGet($xml));
    }

    public function hidStatus($expand = false) {
        $expand = $expand === true ? 'responseFormat="expanded" ' : '';
        $xml = '<hid:Doors action="LR" responseFormat="status" />';

        return $this->xmlParse($this->hidGet($xml));
    }

    public function status2N() {
        $url = "https://$this->host/api/switch/status?switch=1";
        $rtn = $this->URL($url);
        if ($rtn) {
            $rtn = json_decode($rtn);

            // dde("status2N: \n".print_r($rtn,true)", 'hid.log');

            if ($rtn->success && $rtn->result->switches[0]->active)
                $status = 0;
            else
                $status = 1;
        }
        else
            $status = -1;

        dde("status2N: $url $status\n".print_r($rtn,true),'hid.log');

        return $status;
    }

    public function statusBrivo() {
        if ($this->brivo_id) {
            $brivo = new BrivoApi();
            switch ($brivo->doorStatus($this->brivo_id)) {
            case 'LOCKED':
                $status = 0;
                break;
            case 'UNLOCKED':
                $status = 1;
                break;
            default:
                $status = -1;
                break;
            }
        }
        else
            $status = -1;
        return $status;
    }

    public function hidParam() {
        $xml = '<hid:EdgeSoloParameters action="DR"/>';

        return $this->xmlParse($this->hidGet($xml));
    }

    // Set language of controller
    public function hidSetLang($lang='en_EN') {
        $xml = '<hid:EdgeSoloParameters action="UD" language="'.$lang.'" />';

        return $this->xmlParse($this->hidGet($xml));
    }


    // Set Eula
    public function hidSetEula($accept=1) {
        $xml = '<hid:EdgeSoloParameters action="UD" acceptEULA="'.$accept.'" />';

        return $this->xmlParse($this->hidGet($xml));
    }

    public function hidSetTZ($tz='CST') {
        $xml = '<hid:Time action="UD" TZ="'.$tz.'" TZCode="'.$this->tzlist[$tz].'" />';

        return $this->xmlParse($this->hidGet($xml));
    }

    public function hidSetPasswd($passwd='admin') {
        $xml = '<hid:System action="CM" command="restartNetwork" '.
            'userPassword="" adminPassword="'.$passwd.'" />';

        return $this->xmlParse($this->hidGet($xml));
    }


    // Installer Information Page
    public function hidInstall($data=[]) {
        $xml = '<hid:EdgeSoloParameters action="UD" installerLock="0" ';
        $xml .= 'companyName="'.$data['name'].'" ';
        $xml .= 'companyAddress="'.$data['address'].'" ';
        $xml .= 'companyCity="'.$data['city'].'" ';
        $xml .= 'companyState="'.$data['state'].'" ';
        $xml .= 'companyCountry="'.$data['country'].'" ';
        $xml .= 'companyTelephone="'.$data['phone'].'" ';
        $xml .= 'companyEmail="'.$data['email'].'" ';
        $xml .= '/>';

        return $this->xmlParse($this->hidGet($xml));
    }

    // Set Door Name
    public function hidName($name='UrbanSKY') {
        $xml = '<hid:EdgeSoloParameters action="UD" doorName="'.$name.'" />';

        return $this->xmlParse($this->hidGet($xml));
    }

    // Door Parameters
    public function hidParams($params = []) {
        $xml = '<hid:Doors action="UD"><hid:Door ';
        if (array_key_exists('unlock', $params))
            $xml .= 'unlockInterval="'.$params['unlock'].'" ';
        if (array_key_exists('exunlock', $params))
            $xml .= 'extendedUnlockInterval="'.$params['exunlock'].'" ';
        if (array_key_exists('held', $params))
            $xml .= 'heldInterval="'.$params['held'].'" ';
        $xml .= 'REXBehavior="UnlockDoor" ';
        $xml .= 'doorType="1" ';
        $xml .= 'doorMode="1" ';
        $xml .= 'electricalInterface="Wiegand" ';
        $xml .= '/></hid:Doors>';

        return $this->xmlParse($this->hidGet($xml));
    }

    // Send commands to Entry Controller to initialize it.
    public function hidOnboard() {

        $rtn = $this->hidSetLang();
        if  ($this->verbose) echo "Language\n",print_r($rtn,true),"\n";

        $rtn = $this->hidSetEula();
        if  ($this->verbose) echo "EULA\n",print_r($rtn,true),"\n";

        $rtn = $this->hidInstall($this->data);
        if  ($this->verbose) echo "Install\n",print_r($rtn,true),"\n";

        $rtn = $this->hidName($this->door);
        if  ($this->verbose) echo "Name\n",print_r($rtn,true),"\n";

        $rtn = $this->hidParams();
        if  ($this->verbose) echo "Params\n",print_r($rtn,true),"\n";

        $rtn = $this->hidSetTZ();
        if  ($this->verbose) echo "Set TimeZone\n",print_r($rtn,true),"\n";

        $rtn = $this->hidSetTime();
        if  ($this->verbose) echo "Set Time\n",print_r($rtn,true),"\n";

        // This will reboot the device
        $rtn = $this->hidSetPasswd($this->password);
        if  ($this->verbose) echo "Password\n",print_r($rtn,true),"\n";
    }

    public function hidSetAlert($alert=null,$http=null) {
        if ($alert) {
            $xml = '<hid:AlertActions action="UD" eventCode="'.$alert.'" >';
            if ($http)
                $xml .= '<hid:AlertAction actionType = "HTTP" value="'.str_replace('&','&amp;amp;',$http).'" />';
            else
                $xml .= '<hid:AlertAction actionType = "None" />';
            $xml .= '</hid:AlertActions>';
            $vars = $this->xmlParse($this->hidGet($xml));
            if ($this->verbose) {
                print_r($vars);
            }
        }
        return null;
    }

    public function hidSetAlerts($asset_id, $key = 'default', $value='') {
        $rec = Asset::find($asset_id);
        if ($rec) {
            $this->host = $rec->ip_address;
            $this->username = $rec->admin_user;
            $this->password = $rec->admin_pass;
            $this->model_id = $rec->model_id;

            if ($key == 'default') {
                $server = \App\Models\Devices\Server::where('building_id', $rec->building_id)->first();
                if ($server) {
                    $onsite = $server->ip_address;
                    $onsite = "http://$onsite/edge/jpg.php?q=1,$rec->id,";

                    if ($this->verbose) echo "On-site: $onsite \n";
                    if ($rec->has_camera) {
                        $this->hidSetAlert('1022',$onsite.'1,,');
                        $this->hidSetAlert('2020',$onsite.'3,,');
                        $this->hidSetAlert('2021',$onsite.'4,,');
                        $this->hidSetAlert('2024',$onsite.'5,,');
                        $this->hidSetAlert('2036',$onsite.'7,,');
                        $this->hidSetAlert('2043',$onsite.'9,,');
                        $this->hidSetAlert('4034',$onsite.'11,,');
                        $this->hidSetAlert('4045',$onsite.'18,,');
                        $this->hidSetAlert('12031',$onsite.'20,,');
                        $this->hidSetAlert('12032',$onsite.'21,,');
                        $this->hidSetAlert('12033',$onsite.'22,,');
                    }

                    if ($rec->has_door2) {
                        $this->hidSetAlert('4045',$onsite.'22,1,1');
                    }
                    $this->hidSetAlert('12031',$onsite.'20,,');

                    $onsite = str_replace('jpg.php','alarm.php', $onsite);
                    $this->hidSetAlert('4041',$onsite.'14,,');
                    $this->hidSetAlert('4042',$onsite.'15,,');
                    $this->hidSetAlert('4043',$onsite.'16,,');

                    if ($this->verbose) {
                        $vars = $this->hidGetAlerts();
                        if ($this->verbose) {
                            print_r($vars);
                        }
                    }
                    return true;
                }
                else {
                    if ($this->verbose)
                        echo "Unable to determine server for $host\n";
                    else
                        dde("Unable to determine server for $host",'hid.log');
                }
            }
            else {

                if ($key && !empty($value)) {
                    $vars = $this->hidSetAlert($key, $value);
                    if ($this->verbose) print_r($vars);
                }
                else {
                    $vars = $hid->hidGetAlerts();
                    if ($this->verbose) print_r($vars);
                }
            }
        }
        else {
            if ($this->verbose)
                echo "Unable to find Entry Controller: $asset_id\n";
            else
                dde("Unable to find Entry Controller: $asset_id",'hid.log');
        }
        return false;

    }

    public function hidGetAlerts() {
        $xml = '<hid:AlertActions action="LR" />';
        $vars = $this->xmlParse($this->hidGet($xml));
        if ($this->verbose) print_r($vars);
        $alerts = array();
        foreach ($vars as $i => $var) {
            $attr = $var['attributes'];
            $key = $attr['eventCode'];

            $alerts[$key]['type'] = $attr['actionType'];
            if (array_key_exists('value', $attr))
                $alerts[$key]['value'] = str_replace('$amps','&', $attr['value']);
        }
        return $alerts;
    }

    public function hidGetTime() {
        $xml = '<hid:Time action="DR"/>';

        return $this->xmlParse($this->hidGet($xml));
    }

    // Set time on device, retreive time to get timezone.
    public function hidSetTime() {

        $time = $this->hidGetTime();
        if (array_key_exists(0, $time)
          && array_key_exists('attributes', $time[0])) {
            $tzone = $time[0]['attributes']['timeZone'];
            $tz = $time[0]['attributes']['TZ'];
            $tzcode = $time[0]['attributes']['TZCode'];

            // Save Server Timezone
            $systz = date_default_timezone_get();
            // Change to device time zone
            list($newtz) = explode(',', $this->tzlist[$tzone]);
            date_default_timezone_set($newtz);
            $time = getdate();
            // Change back to Server Timezone
            date_default_timezone_set($systz);

            $d['month'] = $time['mon'];
            $d['dayOfMonth'] = $time['mday'];
            $d['year'] = $time['year'];
            $d['hour'] = $time['hours'];
            $d['minute'] = $time['minutes'];
            $d['second'] = $time['seconds'];
            $d['timeZone'] = $tzone;
            $d['TZ'] = $tz;
            $d['TZCode'] = $tzcode;

            $xml = '<hid:Time action="UD" ';
            foreach ($d as $k => $v)
                $xml .= $k.'="'.$v.'" ';
            $xml .= '/>';

            $rtn = $this->hidGet($xml);
        }
        else
            $rtn = '';

        return $this->xmlParse($rtn);
    }

    public function hidNumEvents() {
        $info = $this->hidGet('<hid:EventMessages action="DR"/>');
        $vars = $this->xmlParse($info);

        return $vars[0]['attributes']['eventsInUse'];
    }

    public function hidNumCards() {
        $vars = $this->xmlParse($this->hidGet('<hid:Credentials action="DR"/>'));

        return $vars[0]['attributes']['credentialsInUse'];
    }

    public function hidNumPeople() {
        $vars = $this->xmlParse($this->hidGet('<hid:Cardholders action="DR"/>'));

        return $vars[0]['attributes']['cardholdersInUse'];
    }

    public function hidListParameters() {
        $vars = $this->xmlParse($this->hidGet('<hid:EdgeSoloParameters action="DR"/>'));

        return $vars[0]['attributes'];
    }

    public function hidDoorGrant() {
        $vars = $this->xmlParse($this->hidGet('<hid:Doors action="CM" command="grantAccess"/>'));
        if (isset($vars[0]))
            return $vars[0];
        else
            return null;
        return $vars[0];
    }

    public function hidDoorUnlock() {
        $vars = $this->xmlParse($this->hidGet('<hid:Doors action="CM" command="unlockDoor"/>'));

        if (isset($vars[0]))
            return $vars[0];
        else
            return null;
    }

    public function hidDoorLock() {
        $vars = $this->xmlParse($this->hidGet('<hid:Doors action="CM" command="lockDoor"/>'));
        if (isset($vars[0]))
            return $vars[0];
        else
            return null;
    }

    public function hidDoorLockdown($state = 0) {
        switch ($state) {
        case 1:
            $vars = $this->xmlParse($this->hidGet('<hid:Doors action="CM" command="lockDoor"/>'));
        case 0:
            $vars = $this->xmlParse($this->hidGet('<hid:Doors action="CM" command="unlockDoor"/>'));
            break;
        }
        if (isset($vars[0]))
            return $vars[0];
        else
            return null;
    }

    public function doorGrant2n() {
        $url = "https://$this->host/api/switch/ctrl?switch=1&action=trigger";
        $rtn = $this->URL($url);
        dde("$url: $rtn", '2n.log');
        return json_decode($rtn);
    }

    public function doorUnlock2n() {
        $url = "https://$this->host/api/switch/ctrl?switch=1&action=hold";
        dde("$url", '2n.log');
        $rtn = $this->URL($url);
        dde($rtn, '2n.log');
        return json_decode($rtn);
    }

    public function doorLock2n() {
        $url = "https://$this->host/api/switch/ctrl?switch=1&action=release";
        $rtn = $this->URL($url);
        dde("$url: $rtn", '2n.log');
        return json_decode($rtn);
    }

    public function doorLockdown2n($state = 0) {
        switch($state) {
        case 0:
            $url = "https://$this->host/api/switch/ctrl?switch=1&action=release";
            break;
        case 1:
            $url = "https://$this->host/api/switch/ctrl?switch=1&action=release";
            break;
        }
        $rtn = $this->URL($url);
        dde("$url: $rtn", '2n.log');
        return json_decode($rtn);
    }

    public function doorGrantBrivo() {
        if ($this->brivo_id) {
            $brivo = new BrivoApi();
            $brivo->doorAction($this->brivo_id, 'grant');
        }
        return null;
    }

    public function doorUnlockBrivo() {
        if ($this->brivo_id) {
            $brivo = new BrivoApi();
            $brivo->doorAction($this->brivo_id, 'unlock');
        }
        return null;
    }

    public function doorLockBrivo() {
        if ($this->brivo_id) {
            $brivo = new BrivoApi();
            $brivo->doorAction($this->brivo_id, 'lock');
        }
        return null;
    }

    public function doorLockdownBrivo($state = 0) {
        if ($this->brivo_id) {
            $brivo = new BrivoApi();
            switch ($state) {
            case 1:
                $brivo->doorAction($this->brivo_id, 'lockdown');
                break;
            case 0:
                $brivo->doorAction($this->brivo_id, 'removelockdown');
                break;
            }
        }
        return null;
    }

    public function hidAlarmReset() {
        $vars = $this->xmlParse($this->hidGet('<hid:ControlVariables action="CM" index="101" value="false"/>'));
        return $vars[0];
    }


    public function getPID($asset_id, $user_id) {
        if ($asset_id > 0 and $user_id > 0) {
            $query = "
            SELECT pid
            FROM assets_hid
            WHERE asset_id = $asset_id
                and user_id = $user_id
            ";

            $r = $this->sql_query($query);
            $row = mysql_fetch_row($r);

            return $row[0];
        }
        else
            return null;
    }

    public function getPIDs($asset_id) {
        if ($asset_id > 0) {
            $query = "
            SELECT hid_id, user_id
            FROM assets_hid
            WHERE asset_id = $asset_id
            ";

            $r = $this->sql_query($query);
            $users = array();
            while ($row = $r->fetch_assoc()) {
                extract ($row);
                $users[$pid] = $user_id;
            }

            return $users;
        }
        else
            return null;
    }

    public function addName($loc, $data) {

        $user_id = $data['user_id'];
        if (empty($user_id))
            return;

        $query = "
        SELECT id as asset_id,
            ip_address as host,
            model_id,
            admin_user as username,
            admin_pass as password
        FROM assets
        WHERE building_id = $loc
            and active = 1
        ";

        $r = $this->sql_query($query);

        while ($row = $r->fetch_assoc()) {
            extract($row);
            $this->host = $ip_address;
            $this->username = $username;
            $this->password = $password;
            $this->model_id = $model_id;


            $pid = $this->getPID($asset_id, $user_id);


            // If this PID in the list then update
            if ($pid > 0) {
                echo "Updating $user_id ($pid) for $this->host";
                $vars = $this->hidEditPerson($pid, $data);
                if ($vars[0]['tag'] == 'hid:Error') {
                    echo " Error: ",$vars[0]['attributes']['errorMessage'];
                }
                echo "<br />\n";
            }
            else {
                echo "Adding $user_id ($pid) for $host";
                $vars = $this->hidAddPerson($data);
                if ($vars[0]['tag'] == 'hid:Error') {
                    echo " Error: ",$vars[0]['attributes']['errorMessage'],"<br />\n";
                }

                $pid = $vars[0]['attributes']['cardholderID'];

                // Update lookup for CardHolderID
                if ($asset_id > 0 and $user_id > 0 and $pid > 0) {
                    $query = "INSERT INTO hid (asset_id, user_id, pid) VALUES($asset_id, $user_id, $pid)";
                    $r3 = $this->sql_query($query);
                }
            }

        }
    }

    // Return stat of door, also update database with current state
    public function getDoorState($hid, $states = array()) {

        if ($hid) {
            // $query = "
            // SELECT ip_address, model_id, admin_user, admin_pass, brivo_id
            // FROM assets
            // WHERE id = $hid
            // ";
            // $r = $this->sql_query($query);
            // $row = $r->fetch_assoc();

            $row = Asset::select('ip_address', 'model_id', 'admin_user', 'admin_pass', 'brivo_id')->where('id',$hid)->first();

            $this->host = $row['ip_address'];
            $this->username = $row['admin_user'];
            $this->password = $row['admin_pass'];
            $this->model_id = $row['model_id'];
            $this->brivo_id = $row['brivo_id'];
            dde($row, 'hid.log');

            switch ($this->model_id) {
            case 154:   // 2N Verso
                $s = $this->status2N();
                dde("getDoorState: 2N $s",'hid.log');

                break;
            case 155:   // Brivo ACS300
                $s = $this->statusBrivo();
                dde("$this->brivo_id: $s",'brivo.log');
                break;
            default:
                $vars = $this->hidStatus();
                if (array_key_exists(0, $vars)) {

                    switch ($vars[0]['attributes']['relayState']) {
                    case 'set':
                        $s = '0';
                        break;
                    case 'unset':
                        $s = '1';
                        break;
                    default:
                        $s = '-1';
                        break;
                    }

                }
                else {
                    $s =  null;
                }
            }

            if (count($states))
                return $states[$s];
            else
                return $s;
        }
        else
            return -1;
    }

    // Used to perform action on door, Grant, Unlock or Lock
    public function hidAction($param) {

        $hid = $param['hid'];
        $action = $param['action'];
        if ($hid && $param) {
            // $query = "SELECT * FROM assets WHERE id = \"$hid\"";
            // $r = $this->sql_query($query);
            // $row = $r->fetch_assoc();

            $row = Asset::find($hid);

            $this->host = $row['ip_address'];
            $this->username = $row['admin_user'];
            $this->password = $row['admin_pass'];
            $this->model_id = $row['model_id'];
            $this->brivo_id = $row['brivo_id'];

            dde("$action $this->host $this->model_id $this->brivo_id", 'hid.log');

            switch ($action) {
            case 'grant':
                switch ($this->model_id) {
                case '154':
                    $this->doorGrant2n();
                    break;
                case '155':
                    $this->doorGrantBrivo();
                    break;
                default:
                    $this->hidDoorGrant();
                }
                break;
            case 'lock':
                switch ($this->model_id) {
                case '154':
                    $this->doorLock2n();
                    break;
                case '155':
                    $this->doorLockBrivo();
                    break;
                default:
                    $this->hidDoorLock();
                    break;
                }
                $query = "UPDATE assets SET locked = 1 WHERE id = \"$hid\"";
                $this->sql_query($query);
                break;
            case 'unlock':
                switch ($this->model_id) {
                case '154':
                    $this->doorUnlock2n();
                    break;
                case '155':
                    $this->doorUnlockBrivo();
                    break;
                default:
                    $this->hidDoorUnlock();
                    break;
                }
                $query = "UPDATE assets SET locked = 0 WHERE id = \"$hid\"";
                $this->sql_query($query);
                break;
            case 'lockdown':
                switch ($this->model_id) {
                case '154':    // 2N
                    $this->doorLockdown2n(1);
                    break;
                case '155':     // Brivo
                    $this->doorLockdownBrivo(1);
                    break;
                default:        // HID
                    $this->hidDoorLockdown(1);
                    break;
                }
                break;
            case 'removelockdown':
                switch ($this->model_id) {
                case '154':
                    $this->doorUnlock2n(0);
                    break;
                case '155':
                    $this->doorUnlockdownBrivo(0);
                    break;
                default:
                    $this->hidDoorLockdown(0);
                    break;
                }
                break;
            }
            return $this->getDoorState($hid);
        }
    }


    // Retreive logs
    public function hidLog($d) {
        extract($d);

        $flist = ' ,hid_id,timestamp,event_type,event_data,building_id,asset_id,user_id,unit_id,token_id';
        $vlist = ' ,hid_id,building_id,asset_id,user_id,unit_id,token_id';
        $done = false;
        $query = "
        SELECT timestamp
        FROM assets_log
        WHERE asset_id = $asset_id
            and timestamp = '$timestamp'
            and building_id = $building_id
            and event_type = '$event_type'
        ";
        $r = $this->sql_query($query);
        $row = $r->fetch_assoc();

        // If this log entry exists then set flag and finish
        if (is_array($row)
          && array_key_exists('timestamp', $row)
          && $row['timestamp'] == $timestamp) {
            $done = true;
        }
        // Otherwise add entry to log
        else {
            $flds = $vals = '';
            foreach ($d as $k => $v) {
                if (strpos($flist, $k)) {
                    $flds .= "$k,";
                    if (strpos($vlist, $k)) {
                        if (strlen($v))
                            $vals .= "$v,";
                        else
                            $vals .= "null,";
                    }
                    else {
                        $vals .= "'$v',";
                    }
                }
            }
            $flds = substr($flds, 0, -1);
            $vals = substr($vals, 0, -1);
            $query = "INSERT INTO assets_log ($flds) VALUES($vals)";

            $this->sql_query($query);
        }
        return $done; // return status (true if entry exists)
    }

    public function hidNotify($data) {
        extract ($data);
        /*
        $query = "
        SELECT b.notify,
            b.apt as unit,
            c.notify_email as email,
            c.notify_sms as sms,
            c.notify_smsprovider as carrier,
            c.name as loc_name,
            CONCAT(a.fname,' ',a.lname) as tenant
        FROM people a
        LEFT OUTER JOIN apt b ON (a.apt = b.id)
        LEFT OUTER JOIN loc c ON (b.loc = c.id)
        WHERE a.id = $people_id
        ";

        $r = $this->sql_query($query);
        $row = mysql_fetch_assoc($r);
        if ($row['notify']) {
            $q2 = "SELECT description as rfid_name FROM asset WHERE id = $asset_id";
            $r2 = $this->sql_query($q2);
            $row2 = mysql_fetch_assoc($r2);
            
            $flds = array_merge($row, $row2, $data);

            if ($flds['email'] != '')
                emailNotify($flds);

            if ($flds['sms'] != '')
                smsNotify($flds);
        }
        
        */
    }

    public function hidUpdLastUse($data) {
        extract($data);
        /*

        // Only update if valid
        if ($event_type == '2020') {
            $query = "
            SELECT hid_timestamp
            FROM people
            WHERE id = $people_id
                and hid_timestamp > '$timestamp'
            ORDER BY hid_timestamp desc
            LIMIT 1
            ";
            $r = $this->sql_query($query);
            $row = mysql_fetch_assoc($r);

            // New notification
            if ( $row['hid_timestamp'] < $timestamp ) {
                $query = "
                UPDATE people
                SET hid_timestamp = '$timestamp'
                WHERE id = $people_id
                ";

                $this->sql_query($query);
                // Now check for notification
                hidNotify($data);
                
            }
        }
        */
        return;
    }

    public function numBits($s) {
        $p = 0;
        for($i = 0; $i < strlen($s); $i++) {
            if ($s[$i])
                $p++;
        }
        return $p;
    }


    public function wiegand($card_no = 0) {
        if ($card_no) {
            switch ($this->bits) {
            case 37:
                $bfac = base_convert($this->fac, 10, 2);
                $p = ($this->numBits($bfac)%2) ? '1':'0';
                $bfac = substr('0000000000000000'.$bfac,-16);

                $c = base_convert($card_no, 10, 2);

                $c = $bfac.substr('0000000000000000000'.$c,-19);

                $e = ($this->numBits(substr($c,0,19))%2) ? '1' : '0';  // Even bits
                $o = ($this->numBits(substr($c,18))%2) ? '0' : '1';    // Odd Bits

                $bin = gmp_strval(gmp_init($e.$c.$o, '2'), 16);
                return strtoupper(substr('0000000000'.$bin, -10));
                break;
            }
        }
        return null;
    }

}

<?php

?>

    <!-- <div class="containerHeadline">
        <div style="display:inline-block;"><i class="icon-building"></i><h2><?php if($bldg_id == ''): ?>Buildings<?php else: ?>Portfolio Summary</h2></div><?php endif; ?>
        <div  style="display:inline-block; margin-left: 10px; font-weight: bold;">Last Updated : <span style="color: <?php echo $color ?>"><?php echo isset($last_updated) ? $last_updated: 'Not Set'; ?></span></div>
        <?php if($bldg_id != ''): ?>
        <div  style="display:inline-block;float: right;"><a href="javascript:void(0);" onclick="resetBuildingSelection();" class="mus_link">Reset</a><?php endif; ?></div>
            <?php if($bldg_id != ''): ?><div  style="display:inline-block;float: right;padding-right:2px ">| </div><?php endif; ?>
            <div style="display:inline-block;float: right;padding-right:2px">
                <a href="/laravel/manageLockdown" class="mus_link">
                    Manage Lockdown 
                </a>
            </div>
        </div>
    </div> -->

<!-- <div class="floatingBox table">
    <div class="container-fluid"> -->
        <span style="float:right;color: {{$color}};">Last Updated : 
            {{$last_updated}}    
        </span><br>
        <table class="table" style="width:100%;">
            <thead>
            <tr>
                <th class="dashbordtablesrwidth info sorter-false tablesorter-header" data-column="0">
                    <div class="tablesorter-header-inner">#</div>
                </th>
                <?php
                $i = 1;
                $keys = array("Name", "Lockdown", "ATA", "Entry", "Name Display", "Door Icm", "Router", "Modems", "Onsite", "Cameras", "Video intercom", "Waps", "Switches", "Laundry", "Captive", "Unverified"
                    // "Tv", "Data", "wifi", "phone" , "fobs" , "laundry access" , "Helios (intercom)" , "any cameras" , "credit card processing" , "resident portals"
                );
                // $results = array_combine($keys, $lists[0]);
                // error_log(date('Y-m-d H:i:s ') . print_r($lists, true) . "\n", 3, '/tmp/dashboard.log');
                foreach ($keys as $heads => $val) {
                    $add_class = '';
                    $add_style = '';
                    if ($i > 2) $add_style = 'style="text-align:center"';

                    ?>
                    <th class="dashbordtablewidth" data-placeholder="search in <?php echo $val ?>..."
                        data-column="<?php echo $i++; ?>"
                        class="tablesorter-header sorter-false <?php echo $add_class; ?>" <?php echo $add_style; ?>>
                        <div class="tablesorter-header-inner"><?php echo $val; ?></div>
                    </th>
                <?php }
                 ?>

            </tr>
            </thead>
            <tbody class="text-medium color-secondary">
            <?php
            // $status_array = Configure::read('status');
            $status_array = [];

            if (!empty($lists)) {
                
                foreach ($lists as $key => $list) {
                    /*echo '<pre>';print_r($list);exit;*/
                    $lockDown = $list['lockdown'] == 1;
                    $name = !empty($list['name']) ? $list['name'] : "";
                    $address1 = !empty($list['address1']) ? $list['address1'] : "";
                    $created_on = !empty($list['created']) ? Date('d-m-Y', strtotime($list['created'])) : "";
                    $status = isset($status_array[$list['status']]) ? $status_array[$list['status']] : "";
                    $fob_status = isset($list['fob_status']) ? $list['fob_status'] : "";
                    $added_by = isset($list['firstname']) ? $list['firstname'] . " " . $list['lastname'] : "";
                    $usertype = !empty($list['usertype_name']) ? $list['usertype_name'] : "-";
                    $is_register = !empty($list['is_register']) ? $list['is_register'] : "";
                    $ata_status = '-';
                    $entry = '-';
                    $name_display = '-';
                    $door_status = '-';
                    $router_status = '-';
                    $modem_status = '-';
                    $onsite_server = '-';
                    $cameras = '-';
                    $video_intercom = '-';
                    $waps = '-';
                    $poe = '-';
                    $ip_power = '-';
                    $laundry = '-';
                    $captive = '-';
                    $unverified = '-';
                    if (isset($list['ata_status']) && $list['ata_status'] != '') {
                        $ata_status = $list['ata_status'];
                    }
                    if (isset($list['entry']) && $list['entry'] != '') {
                        $entry = $list['entry'];
                    }
                    if (isset($list['name_display']) && $list['name_display'] != '') {
                        $name_display = $list['name_display'];
                    }
                    if (isset($list['door_status']) && $list['door_status'] != '') {
                        $door_status = $list['door_status'];
                    }
                    if (isset($list['router_status']) && $list['router_status'] != '') {
                        $router_status = $list['router_status'];
                    }
                    if (isset($list['modem_status']) && $list['modem_status'] != '') {
                        $modem_status = $list['modem_status'];
                    }
                    if (isset($list['onsite_server']) && $list['onsite_server'] != '') {
                        $onsite_server = $list['onsite_server'];
                    }
                    if (isset($list['cameras']) && $list['cameras'] != '') {
                        $cameras = $list['cameras'];
                    }
                    if (isset($list['video_intercom']) && $list['video_intercom'] != '') {
                        $video_intercom = $list['video_intercom'];
                    }
                    if (isset($list['waps']) && $list['waps'] != '') {
                        $waps = $list['waps'];
                    }
                    if (isset($list['poe']) && $list['poe'] != '') {
                        $poe = $list['poe'];
                    }
                    if (isset($list['laundry']) && $list['laundry'] != '') {
                        $laundry = $list['laundry'];
                    }
                    if (isset($list['captive']) && $list['captive'] != '') {
                        $captive = $list['captive'];
                    }
                    if (isset($list['unverified_resident'])) {
                        $unverified = $list['unverified_resident'];
                    }
                    ?>
                    <tr style="">
                        <td style="text-align: left;"><?php echo $key + 1; ?></td>
                        <td class="lockdown-background-<?php echo $list['building_id']; ?>" style="text-align: left; <?php if ($lockDown): ?> background-color:red;  <?php endif; ?>">
                            <a class="lockdown-a-<?php echo $list['building_id']; ?>"
                                <?php if ($lockDown): ?>
                                    style="color: white; font-weight:300px" title="Building is in lockdown."
                                <?php endif; ?>
                                    href="/building/manage/{{$list['building_id']}}"
                                <?php if ($bldg_id != $list['building_id']) { ?>
                                    onclick="if (confirm([&quot;Do you want to  <?php if (isset($building_name) && $building_name != '') echo 'exit ' . ucfirst($building_name) . ' and '; ?>view <?php echo ucfirst($name); ?>?&quot;]))
                                            { undelete_user(this.id); } return false;" <?php } ?>>
                                <?php echo $name ?>
                                <?php if ($lockDown): ?> 
                                    <a href="/lockdown/manage">
                                        (Manage)
                                    </a>                
                                <?php endif; ?>
                                <?php if ($is_admin == 1): ?>
                                <a href="javascript:void(0);"
                                    onclick="showFobPopup('<?php echo $list['building_id']; ?>')"><?php echo $fob_status; ?></a><?php endif; ?>
                                </a>
                            </a>
                        </td>
                        <td style="text-align: center;">

                                <div class="demo settingToggler" id="default">
                                    
                                    <input class="m-mode" name="lockdownBuildings" value="{{$list['building_id']}}" data-onstyle="danger" data-offstyle="success" @if($lockDown) checked @endif
                                            data-width="100" data-height="30" type="checkbox" onchange="activateOrDeactivateLockdown(<?php echo $list['building_id']; ?>,event)"
                                        >
                                </div>


                        </td>
                        <td class="dashboardtablerow"><?php echo $ata_status; ?></td>
                        <td class="dashboardtablerow"><?php echo $entry; ?></td>
                        <td class="dashboardtablerow"><?php echo $name_display; ?></td>
                        <td class="dashboardtablerow"><?php echo $door_status; ?></td>
                        <td class="dashboardtablerow"><?php echo $router_status; ?></td>
                        <td class="dashboardtablerow"><?php echo $modem_status; ?></td>
                        <td class="dashboardtablerow"><?php echo $onsite_server; ?></td>
                        <td class="dashboardtablerow"><?php echo $cameras; ?></td>
                        <td class="dashboardtablerow"><?php echo $video_intercom; ?></td>
                        <td class="dashboardtablerow"><?php echo $waps; ?></td>
                        <td class="dashboardtablerow"><?php echo $poe; ?></td>
                        <td class="dashboardtablerow"><?php echo $laundry; ?></td>
                        <td class="dashboardtablerow"><?php echo $captive; ?></td>
                        <td class="dashboardtablerow"><?php echo $unverified; ?></td>
                        <?php if (false) { ?>
                            
                        <?php } ?>

                    </tr>
                    <?php
                }
            } else {

                ?>
                <tr>
                    <td colspan="3">
                    
                    </td>
                </tr>
            <?php }

            ?>
            </tbody>
        </table>
        
    <!-- </div>
</div>  -->

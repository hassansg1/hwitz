@php
    use \App\Http\Controllers\HomeController;
    HomeController::amenities_status();

    $disable_amenities_subscription = session()->get('disable_amenities_subscription');
    $is_verified_user = session()->get('is_verified_user');
    $task_details_layuout = session()->get('task_details_layuout');
    $internet = session()->get('internet');
    $unit_id = session()->get('unit_id');
    $subscribed_services = session()->get('subscribed_services');
    $user = session()->get('user');
@endphp

<div class="container">
    <div class="contentBox responsiveBox">
        <!-- options visible start from here -->
        <div class="text-left">
            <div style=" font-size: 16px;padding-bottom: 20px; ">
                @if (Auth::user()->usertype_id == '13')

                    {{-- echo $disable_amenities_subscription --}}
                    @if($disable_amenities_subscription == 0)
                        <strong>Amenities Status: </strong>
                    @endif

                    @if(count($task_details_layuout) > 0)
                        @if ($disable_amenities_subscription == 0)
                            Service Interrupted.<br/><br/>
                        @endif
                        <div class="@if($is_verified_user) blue-area @else gray-area @endif" style="margin-left: 0px;">
                            <a href="{{url('dashboard/pending_tasks')}}"
                               class="@if($is_verified_user) ach-blue @else anch-color-green @endif">Service was
                                interrupted click to fix
                            </a>
                        </div>
                    @elseif (isset($internet['Buildingpackageaddons']) && ($internet['Buildingpackageaddons']['is_included'] == 0 && $internet['Buildingpackageaddons']['is_added'] == 0))
                        @if (!$disable_amenities_subscription)
                            Not yet subscribed.<br><br>
                        @endif
                        <div class="@if($is_verified_user) blue-area @else: gray-area @endif" style="margin-left: 0px;">
                            <a href="#"
                               class="@if ($is_verified_user) ach-blue @else anch-color-green @endif"> Subscribe now
                            </a>
                        </div>
                    @else
                        @php
                            $amenities = array();
                            if (isset($subscribed_services)) {
                                foreach ($subscribed_services as $service) {
                                    $amenities[] = $service['Packagegroup']['group_name'];
                                }
                                if (!$disable_amenities_subscription)
                                    echo count($amenities) ? "Active  (" . implode(', ', $amenities) . ")<br>" : 'Not yet subscribed <br><br>';
                            }
                        @endphp
                        @if ($internet && $user->unit->is_internet==1)
                            <div class="@if ($is_verified_user) green-area @else gray-area test @endif"
                                 style="display: inline; padding: 5px; font-weight: bold; margin-left: 0px;border-radius: 5%; pointer-events:auto;">
                                @if($buildingVar->pre_boarding && $user->unit->onboarding < 3 && $user->unit->guarantor_user_id != $user->id)
                                    <a href="javascript:void(0);"
                                       onclick="alert('Amenity services will be available after the unit guarantor logs into their Urban Sky account and completes the onboarding process.')"
                                       class="@if ($is_verified_user) anch-color-white @else anch-color-green @endif">Take
                                        me to
                                        the Internet.</a>
                                @else
                                    <a href="@if ($is_verified_user)  {!! url("/internet/index/$user->id") !!} @else  javascript:void(0); @endif"
                                       class="@if ($is_verified_user) anch-color-white @else anch-color-green @endif">Take
                                        me to
                                        the Internet.</a>
                                @endif

                            </div>

                            {{-- <div class="gray-area" style="display: inline; padding: 5px; font-weight: bold; margin-left: 0px;border-radius: 5%; pointer-events:auto;">
                                <a href="javascript:void(0);" class="anch-color-green">Take me to the Internet.</a>
                                </div>
                            --}}
                        @endif
                    @endif

                    @if($disable_amenities_subscription==0)
                        @if ($internet && $user->unit->is_internet!=1)
                            @if (count($subscribed_services))

                                <div class="@if($is_verified_user) green-area @else gray-area @endif"
                                     style="display: inline; padding: 5px; font-weight: bold; margin-left: 5px;border-radius: 5%;">
                                    <a href="{{url('resident_order_form')}}"
                                       class="@if ($is_verified_user) anch-color-white @else anch-color-green @endif">Upgrade
                                        Amenities</a>
                                </div>
                            @else
                                @if($disable_amenities_subscription==0 && checkIfGuarantorIsLoggedIn() && unit('performing') == 1)
                                    <div class="gray-area" style="margin-left: 5px; cursor: pointer">
                                        <a href="{{url('resident_order_form')}}"
                                           class="@if ($is_verified_user) ach-blue @else anch-color-green @endif">
                                            Subscribe now </a>
                                    </div>
                                @endif
                            @endif
                        @endif
                    @endif

                @else
                    {{--                    <div class="@if (isset($is_verified_user) && $is_verified_user) green-area @else gray-area @endif"--}}
                    {{--                         style="display: inline; padding: 5px; font-weight: bold; margin-left: 0px;border-radius: 5%; pointer-events:auto;">--}}
                    {{--                        <a href="--}}
                    {{--                            @if( isset($is_verified_user) && $is_verified_user)  {!! Router::url('/internet/index/$user_id') !!}--}}
                    {{--                        @else javascript:void(0);--}}
                    {{--                            @endif"--}}
                    {{--                           class="@if(isset($is_verified_user) && $is_verified_user) anch-color-white @else anch-color-green @endif">Take--}}
                    {{--                            me to the Internet.--}}
                    {{--                        </a>--}}
                    {{--                    </div>--}}
                @endif
            </div>
        </div>
        <!-- options visible ends here -->
    </div>
</div>

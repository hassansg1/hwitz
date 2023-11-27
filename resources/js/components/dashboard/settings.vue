<template>
  <div class="dashboard-settings">
    <div class="mb-28">
<!--      <div class="mb-70">-->
<!--        <vitalSigns :loadedAsAComponentInDashboard="true" />-->
<!--      </div>-->

      <div class="mb-70">
        <div>
          <h4 class="h4-bold mb-28 select-cursor">
            Alert Settings ({{ activeAlertName }})
            <i
              class="ms-24 fa fa-ellipsis-h color-secondary"
              id="dropdownVacate"
              data-bs-toggle="dropdown"
              aria-expanded="false"
            ></i>

            <div class="dropdown">
              <ul
                class="dropdown-menu dropdown-menu-end border-standered"
                aria-labelledby="dropdownVacate"
              >
                <li
                  class="px-2 py-1 border-bottom text-sm-bold color-secondary"
                >
                  Action
                </li>
                <li class="px-2 py-1 border-bottom text-sm-bold select-cursor" @click="loadAlertSettings(1, 'Entry Notification')">
                  Entry Notification
                </li>
                <li class="px-2 py-1 border-bottom text-sm-bold select-cursor" @click="loadAlertSettings(2, 'Mail Code Entry')">
                  Mail Code Entry
                </li>
                <li class="px-2 py-1 border-bottom text-sm-bold select-cursor" @click="loadAlertSettings(3, 'Entry Controller FOB Failure')">
                  Entry Controller FOB Failure
                </li>

                <li class="px-2 py-1 border-bottom text-sm-bold select-cursor" @click="loadAlertSettings(4, 'Tilt / Impact Laundry')">
                  Tilt / Impact Laundry
                </li>
                <li class="px-2 py-1 border-bottom text-sm-bold select-cursor" @click="loadAlertSettings(5, 'Laundry Out of Order')">
                  Laundry Out of Order
                </li>
                <li class="px-2 py-1 border-bottom text-sm-bold select-cursor" @click="loadAlertSettings(6, 'Porting Request')">
                  Porting Request
                </li>
                <li class="px-2 py-1 border-bottom text-sm-bold select-cursor" @click="loadAlertSettings(7, 'Message Notification')">
                  Message Notification
                </li>
                <li class="px-2 py-1 border-bottom text-sm-bold select-cursor" @click="loadAlertSettings(8, 'Work Order Notification')">
                  Work Order Notification
                </li>
              </ul>
            </div>
          </h4>
        </div>
        <div class="mb-28">
          <div>
            <div class="table-responsive">
              <table>
                <tr>
                  <th>Name</th>
                  <th>Mobile</th>
                  <th>Email</th>
                </tr>
                <!-- <tr v-if="alertSettingData.owner">
                  <td>
                    <div class="d-flex">
                      <div class="avatar-sm me-9">
                        <img :src="alertSettingData.owner.profile_picture && alertSettingData.owner.profile_picture != '' ? alertSettingData.owner.profile_picture : '/images/default-user.jpg'" class="avatar-img" alt="card"/>
                      </div>
                      <div class="align-self-center">
                        <div class="text-medium-bold">{{alertSettingData.owner.name}} <span class="color-secondary">({{ alertSettingData.owner.user_type }})</span></div>
                      </div>
                    </div>
                  </td>

                  <td class="w-50">
                    <div class="d-flex me-12">
                      <div>
                        <input class="form-check-input me-12" type="checkbox" v-model="alertSettingData.owner.sms" id="flexCheckDefault" :disabled="alertSettingData.owner.mobile_verification != 'Yes' || alertSettingData.owner.email_verified != 1"/>
                      </div>
                      <div class="align-self-center text-medium mt-6" :class="{'color-primary' : alertSettingData.owner.mobile_verification == 'Yes' , 'color-danger' : alertSettingData.owner.mobile_verification != 'Yes' }">
                        {{alertSettingData.owner.mobile}}
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="text-medium color-secondary">
                      <div class="d-flex me-12">
                      <div>
                        <input class="form-check-input me-12" type="checkbox" v-model="alertSettingData.owner.nemail" id="flexCheckDefault" :disabled="alertSettingData.owner.mobile_verification != 'Yes' || alertSettingData.owner.email_verified != 1"/>
                      </div>
                      <div class="align-self-center text-medium mt-6" :class="{'color-primary' : alertSettingData.owner.email_verified == 1 , 'color-danger' : alertSettingData.owner.email_verified != 1}">
                        {{alertSettingData.owner.email}}
                      </div>
                    </div>
                    </div>
                  </td>
                </tr> -->
                <tr class="border_bottom" v-for="user in alertSettingData.unit_users">
                  <td>
                    <div class="d-flex">
                      <div class="avatar-sm me-9">
                        <img
                          :src="user.profile_picture && user.profile_picture != '' ? user.profile_picture : '/images/default-user.jpg'"
                          class="avatar-img"
                          alt="card"
                        />
                      </div>
                      <div class="align-self-center">
                        <div class="text-medium-bold">{{user.name}} <span class="color-secondary">({{ user.id == $gate.user.id ? 'Owner' : user.unitype_name }})</span></div>
                      </div>
                    </div>
                  </td>

                  <td class="w-50">
                    <div class="d-flex me-12">
                      <div>
                        <input class="form-check-input me-12" type="checkbox" v-model="user.sms" id="flexCheckDefault" :disabled="user.mobile_verification != 'Yes' || user.email_verified != 1"/>
                      </div>
                      <div class="align-self-center text-medium mt-6" :class="{'color-primary' : user.mobile_verification == 'Yes' , 'color-danger' : user.mobile_verification != 'Yes' }">
                        {{user.mobile}}
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="text-medium color-secondary">
                      <div class="d-flex me-12">
                      <div>
                        <input class="form-check-input me-12" type="checkbox" v-model="user.nemail" id="flexCheckDefault" :disabled="user.mobile_verification != 'Yes' || user.email_verified != 1"/>
                      </div>
                      <div class="align-self-center text-medium mt-6" :class="{'color-primary' : user.email_verified == 1 , 'color-danger' : user.email_verified != 1}">
                        {{user.email}}
                      </div>
                    </div>
                    </div>
                  </td>
                </tr>
              </table>
            </div>
          </div>

          <div class="d-flex justify-content-between mt-11">
            <div class="d-flex align-self-center">
              <p class="text-normal-bold-lg color-primary me-24">Verified</p>
              <p class="text-normal-bold-lg color-danger">Unverified</p>
            </div>
            <div class="align-self-center">
              <button class="btn bg-dark text-white" type="button" @click="saveAlertSettings">Submit</button>
            </div>
          </div>
        </div>

        <!-- <div class="d-flex justify-content-center">
          <span class="pagination-btn">
            <img src="/images/icons/left-arrow.png" />
          </span>
          <span class="pagination-btn active">1</span>
          <span class="pagination-btn">2</span>
          <span class="pagination-btn">3</span>
          <span class="pagination-btn">
            <img src="/images/icons/right-arrow.png" />
          </span>
        </div> -->
      </div>

      <div class="mb-70">
        <h4 class="h4-bold mb-28">FOB Settings</h4>
        <div class="mb-28">
          <div>
            <div class="table-responsive">
              <table>
                <tr>
                  <th>Name</th>
                  <th></th>
                </tr>

                <tr class="border_bottom">
                  <td>
                    <span class="color-secondary">Require verification for fob assignments?</span>
                  </td>
                  <td>
                    <div>
                      <input class="form-check-input me-12" type="checkbox" v-model="building.require_fob_verification" id="flexCheckDefault"/>
                    </div>
                  </td>
                </tr>
              </table>
            </div>
          </div>
        </div>
        <div class="d-flex justify-content-end mt-11">
          <div class="align-self-center">
            <button class="btn bg-dark text-white" type="button" @click="saveFobSettings">Submit</button>
          </div>
        </div>
      </div>

      <div class="mb-70">
        <h4 class="h4-bold mb-28">Link buildings</h4>
        <div class="mb-28">
          <div>
            <div class="table-responsive">
              <table>
                <tr>
                  <th>Name</th>
                  <th></th>
                </tr>

                <tr class="border_bottom">
                  <td>
                    <span class="color-secondary">Link parking buildings</span>
                  </td>
                  <td style="width : 25%">
                    <div class="multiselect-border multiselect-bold mb-16">
                        <div class="dropdown-wrapper">
                            <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                                v-model="selectedParkingBuilding"
                                placeholder=""
                                label="name"
                                track-by="id"
                                :multiple="true"
                                :options="parkingBuildings"
                            ></multiselect>
                        </div>
                        <has-error :form="form" field="connection_type"></has-error>
                    </div>
                  </td>
                </tr>
                <tr class="border_bottom">
                  <td>
                    <span class="color-secondary">Link storage buildings</span>
                  </td>
                  <td style="width : 25%">
                    <div class="multiselect-border multiselect-bold mb-16">
                        <div class="dropdown-wrapper">
                            <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                                v-model="selectedStorageBuilding"
                                placeholder=""
                                label="name"
                                track-by="id"
                                :multiple="true"
                                :options="storageBuildings"
                            ></multiselect>
                        </div>
                        <has-error :form="form" field="connection_type"></has-error>
                    </div>
                  </td>
                </tr>
              </table>
            </div>
          </div>
        </div>
        <div class="d-flex justify-content-end mt-11">
          <div class="align-self-center">
            <button class="btn bg-dark text-white" type="button" @click="saveLinkedBuildings">Submit</button>
          </div>
        </div>
      </div>

      <div v-if="this.showBuildingSettings">
      <div class="mb-64">
        <h4 class="h4-bold mb-28 text-capitalize">Resident portal settings</h4>
        <div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="d-flex justify-content-between mb-4">
              <div class="text-medium-bold">Pending Task</div>
              <div>
                <div class="d-flex mt-11">
                  <div class="text-medium color-secondary me-12">{{building.has_pending_task ? 'On' : 'Off'}}</div>
                  <div class="form-check form-switch p-0">
                    <label class="switch mb-0">
                      <input type="checkbox" v-model="building.has_pending_task" role="switch" @change="saveBuildingSettings"  />
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="text-medium-bold">Laundry / Laundry Cart</div>
              <div>
                <div class="d-flex mt-11">
                  <div class="text-medium color-secondary me-12">{{ building.enable_laundry_cart ? 'On' : 'Off'}}</div>
                  <div class="form-check form-switch p-0">
                    <label class="switch mb-0">
                      <input type="checkbox" v-model="building.enable_laundry_cart" role="switch" @change="saveBuildingSettings" />
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="text-medium-bold">ROF / Amenities Cart</div>
              <div>
                <div class="d-flex mt-11">
                  <div class="text-medium color-secondary me-12">{{building.disable_amenities_subscription ? 'On' : 'Off'}}</div>
                  <div class="form-check form-switch p-0">
                    <label class="switch mb-0">
                      <input type="checkbox" v-model="building.disable_amenities_subscription" role="switch" @change="saveBuildingSettings" />
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="text-medium-bold">Rent Cart</div>
              <div>
                <div class="d-flex mt-11">
                  <div class="text-medium color-secondary me-12">{{building.enable_rent_cart ? 'On' : 'Off'}}</div>
                  <div class="form-check form-switch p-0">
                    <label class="switch mb-0">
                      <input type="checkbox" v-model="building.enable_rent_cart" role="switch" @change="saveBuildingSettings" />
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="ms-16 text-medium-bold">
                Do you want to include rent in the rent car?
              </div>
              <div>
                <div class="d-flex mt-11">
                  <div class="text-medium color-secondary me-12">{{building.include_rent_in_additional_rent ? 'On' : 'Off'}}</div>
                  <div class="form-check form-switch p-0">
                    <label class="switch mb-0">
                      <input type="checkbox" v-model="building.include_rent_in_additional_rent" role="switch" @change="saveBuildingSettings" />
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="text-medium-bold">Payments</div>
              <div>
                <div class="d-flex mt-11">
                  <div class="text-medium color-secondary me-12">{{building.has_payment ? 'On' : 'Off'}}</div>
                  <div class="form-check form-switch p-0">
                    <label class="switch mb-0">
                      <input type="checkbox" v-model="building.has_payment" role="switch" @change="saveBuildingSettings" />
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="ms-16 text-medium-bold">Carts</div>
              <div>
                <div class="d-flex mt-11">
                  <div class="text-medium color-secondary me-12">{{building.has_carts ? 'On' : 'Off'}}</div>
                  <div class="form-check form-switch p-0">
                    <label class="switch mb-0">
                      <input type="checkbox" v-model="building.has_carts" role="switch" @change="saveBuildingSettings" />
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="ms-16 text-medium-bold">E-Wallet</div>
              <div>
                <div class="d-flex mt-11">
                  <div class="text-medium color-secondary me-12">{{building.enable_create_e_wallet ? 'On' : 'Off'}}</div>
                  <div class="form-check form-switch p-0">
                    <label class="switch mb-0">
                      <input type="checkbox" v-model="building.enable_create_e_wallet" role="switch" @change="saveBuildingSettings" />
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="text-medium-bold">Documents</div>
              <div>
                <div class="d-flex mt-11">
                  <div class="text-medium color-secondary me-12">{{building.has_documents ? 'On' : 'Off'}}</div>
                  <div class="form-check form-switch p-0">
                    <label class="switch mb-0">
                      <input type="checkbox" v-model="building.has_documents" role="switch" @change="saveBuildingSettings" />
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="ms-16 text-medium-bold">Pending Documents</div>
              <div>
                <div class="d-flex mt-11">
                  <div class="text-medium color-secondary me-12">{{building.has_pending_documents ? 'On' : 'Off'}}</div>
                  <div class="form-check form-switch p-0">
                    <label class="switch mb-0">
                      <input type="checkbox" v-model="building.has_pending_documents" role="switch" @change="saveBuildingSettings" />
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="ms-16 text-medium-bold">Archives</div>
              <div>
                <div class="d-flex mt-11">
                  <div class="text-medium color-secondary me-12">{{building.has_archives ? 'On' : 'Off'}}</div>
                  <div class="form-check form-switch p-0">
                    <label class="switch mb-0">
                      <input type="checkbox" v-model="building.has_archives" role="switch" @change="saveBuildingSettings" />
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="text-medium-bold">Transactions</div>
              <div>
                <div class="d-flex mt-11">
                  <div class="text-medium color-secondary me-12">{{building.has_transactions ? 'On' : 'Off'}}</div>
                  <div class="form-check form-switch p-0">
                    <label class="switch mb-0">
                      <input type="checkbox" v-model="building.has_transactions" role="switch" @change="saveBuildingSettings" />
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="text-medium-bold">Work Order / History</div>
              <div>
                <div class="d-flex mt-11">
                  <div class="text-medium color-secondary me-12">{{building.has_work_order ? 'On' : 'Off'}}</div>
                  <div class="form-check form-switch p-0">
                    <label class="switch mb-0">
                      <input type="checkbox" v-model="building.has_work_order" role="switch" @change="saveBuildingSettings" />
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="text-medium-bold">Package Delivery / History</div>
              <div>
                <div class="d-flex mt-11">
                  <div class="text-medium color-secondary me-12">{{building.has_package ? 'On' : 'Off'}}</div>
                  <div class="form-check form-switch p-0">
                    <label class="switch mb-0">
                      <input type="checkbox" v-model="building.has_package" role="switch" @change="saveBuildingSettings" />
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="text-medium-bold">Unit History</div>
              <div>
                <div class="d-flex mt-11">
                  <div class="text-medium color-secondary me-12">{{building.has_unit_history ? 'On' : 'Off'}}</div>
                  <div class="form-check form-switch p-0">
                    <label class="switch mb-0">
                      <input type="checkbox" v-model="building.has_unit_history" role="switch" @change="saveBuildingSettings" />
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="text-medium-bold">Messages</div>
              <div>
                <div class="d-flex mt-11">
                  <div class="text-medium color-secondary me-12">{{building.has_messages ? 'On' : 'Off'}}</div>
                  <div class="form-check form-switch p-0">
                    <label class="switch mb-0">
                      <input type="checkbox" v-model="building.has_messages" role="switch" @change="saveBuildingSettings" />
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="text-medium-bold">Resources</div>
              <div>
                <div class="d-flex mt-11">
                  <div class="text-medium color-secondary me-12">{{building.has_resources ? 'On' : 'Off'}}</div>
                  <div class="form-check form-switch p-0">
                    <label class="switch mb-0">
                      <input type="checkbox" v-model="building.has_resources" role="switch" @change="saveBuildingSettings" />
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="ms-16 text-medium-bold">FAQ</div>
              <div>
                <div class="d-flex mt-11">
                  <div class="text-medium color-secondary me-12">{{building.has_faq ? 'On' : 'Off'}}</div>
                  <div class="form-check form-switch p-0">
                    <label class="switch mb-0">
                      <input type="checkbox" v-model="building.has_faq" role="switch" @change="saveBuildingSettings" />
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="ms-16 text-medium-bold">Port-in</div>
              <div>
                <div class="d-flex mt-11">
                  <div class="text-medium color-secondary me-12">{{building.has_portin ? 'On' : 'Off'}}</div>
                  <div class="form-check form-switch p-0">
                    <label class="switch mb-0">
                      <input type="checkbox" v-model="building.has_portin" role="switch" @change="saveBuildingSettings" />
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="ms-16 text-medium-bold">Videos</div>
              <div>
                <div class="d-flex mt-11">
                  <div class="text-medium color-secondary me-12">{{building.has_videos ? 'On' : 'Off'}}</div>
                  <div class="form-check form-switch p-0">
                    <label class="switch mb-0">
                      <input type="checkbox" v-model="building.has_videos" role="switch" @change="saveBuildingSettings" />
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="ms-16 text-medium-bold">Charge Billing Service Fee For Amenities Cart	</div>
              <div>
                <div class="d-flex mt-11">
                  <div class="text-medium color-secondary me-12">{{building.billing_service_fee_amenities ? 'On' : 'Off'}}</div>
                  <div class="form-check form-switch p-0">
                    <label class="switch mb-0">
                      <input type="checkbox" v-model="building.billing_service_fee_amenities" role="switch" @change="saveBuildingSettings" />
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="ms-16 text-medium-bold">Charge Billing Service Fee For Rent Cart	</div>
              <div>
                <div class="d-flex mt-11">
                  <div class="text-medium color-secondary me-12">{{building.billing_service_fee_additional_rent ? 'On' : 'Off'}}</div>
                  <div class="form-check form-switch p-0">
                    <label class="switch mb-0">
                      <input type="checkbox" v-model="building.billing_service_fee_additional_rent" role="switch" @change="saveBuildingSettings" />
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="ms-16 text-medium-bold">Resident Annual Re-Verification(Email & Mobile)	</div>
              <div>
                <div class="d-flex mt-11">
                  <div class="text-medium color-secondary me-12">{{building.resident_verification ? 'On' : 'Off'}}</div>
                  <div class="form-check form-switch p-0">
                    <label class="switch mb-0">
                      <input type="checkbox" v-model="building.resident_verification" role="switch" @change="saveBuildingSettings" />
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>


      <div class="mb-64">
        <h4 class="h4-bold mb-28 text-capitalize">Onboarding</h4>
        <div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="text-medium-bold">Enable Profile Image Verification Required</div>
              <div>
                <div class="d-flex mt-11">
                  <div class="text-medium color-secondary me-12">{{building.image_approval ? 'On' : 'Off'}}</div>
                  <div class="form-check form-switch p-0">
                    <label class="switch mb-0">
                      <input type="checkbox" v-model="building.image_approval" @change="saveBuildingSettings"  />
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="text-medium-bold">Enable Verification (Includes Email & Mobile) Required</div>
              <div>
                <div class="d-flex mt-11">
                  <div class="text-medium color-secondary me-12">	See Super Admin To Manage (default is on)</div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="text-medium-bold">Enable Pre Boarding Documents (Includes Lease / Notices , Excludes EULA /Urbansky Lease Addendum)</div>
              <div>
                <div class="d-flex mt-11">
                  <div class="text-medium color-secondary me-12">{{building.pre_boarding ? 'On' : 'Off'}}</div>
                  <div class="form-check form-switch p-0">
                    <label class="switch mb-0">
                      <input type="checkbox" v-model="building.pre_boarding" role="switch" @change="saveBuildingSettings" />
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="text-medium-bold">Orientation Required</div>
              <div>
                <div class="d-flex mt-11">
                  <div class="text-medium color-secondary me-12">{{building.orientation ? 'On' : 'Off'}}</div>
                  <div class="form-check form-switch p-0">
                    <label class="switch mb-0">
                      <input type="checkbox" v-model="building.orientation" role="switch" @change="saveBuildingSettings" />
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="ms-16 text-medium-bold">
                Tasks Required
              </div>
              <div>
                <div class="d-flex mt-11">
                  <div class="text-medium color-secondary me-12">See Super Admin To Manage (default is on)</div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="text-medium-bold">Lease Auto Renewal</div>
              <div>
                <div class="d-flex mt-11">
                  <div class="text-medium color-secondary me-12">{{building.lease_auto_renewal ? 'On' : 'Off'}}</div>
                  <div class="form-check form-switch p-0">
                    <label class="switch mb-0">
                      <input type="checkbox" v-model="building.lease_auto_renewal" role="switch" @change="saveBuildingSettings" />
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="text-medium-bold">Addendum Auto Renewal	</div>
              <div>
                <div class="d-flex mt-11">
                  <div class="text-medium color-secondary me-12">{{building.addendum_auto_renewal ? 'On' : 'Off'}}</div>
                  <div class="form-check form-switch p-0">
                    <label class="switch mb-0">
                      <input type="checkbox" v-model="building.addendum_auto_renewal" role="switch" @change="saveBuildingSettings" />
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="text-medium-bold">ROF Auto Renewal</div>
              <div>
                <div class="d-flex mt-11">
                  <div class="text-medium color-secondary me-12">{{building.rof_auto_renewal ? 'On' : 'Off'}}</div>
                  <div class="form-check form-switch p-0">
                    <label class="switch mb-0">
                      <input type="checkbox" v-model="building.rof_auto_renewal" role="switch" @change="saveBuildingSettings" />
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="mb-64">
        <h4 class="h4-bold mb-28 text-capitalize">ROF</h4>
        <div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="text-medium-bold">Resident Order Form</div>
              <div>
                <div class="d-flex mt-11">
                  <div class="text-medium color-secondary me-12">{{building.disable_service_order_form ? 'On' : 'Off'}}</div>
                  <div class="form-check form-switch p-0">
                    <label class="switch mb-0">
                      <input type="checkbox" v-model="building.disable_service_order_form" @change="saveBuildingSettings"  />
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="text-medium-bold">Parking also Self Managed By Resident	</div>
              <div>
                <div class="d-flex mt-11">
                  <div class="text-medium color-secondary me-12">{{building.parking_resident_controlled ? 'On' : 'Off'}}</div>
                  <div class="form-check form-switch p-0">
                    <label class="switch mb-0">
                      <input type="checkbox" v-model="building.parking_resident_controlled" @change="saveBuildingSettings"  />
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="text-medium-bold">Storage Self Managed By Resident	</div>
              <div>
                <div class="d-flex mt-11">
                  <div class="text-medium color-secondary me-12">{{building.storage_resident_controlled ? 'On' : 'Off'}}</div>
                  <div class="form-check form-switch p-0">
                    <label class="switch mb-0">
                      <input type="checkbox" v-model="building.storage_resident_controlled" role="switch" @change="saveBuildingSettings" />
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="text-medium-bold">Early Termination Fee Switch</div>
              <div>
                <div class="d-flex mt-11">
                  <div class="text-medium color-secondary me-12">{{building.early_termination_fee_switch ? 'On' : 'Off'}}</div>
                  <div class="form-check form-switch p-0">
                    <label class="switch mb-0">
                      <input type="checkbox" v-model="building.early_termination_fee_switch" role="switch" @change="saveBuildingSettings()" />
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="ms-16 text-medium-bold">
                Early Termination Fee Default Cart
              </div>
              <div>
                <div class="d-flex mt-11">
                  <div class="text-medium color-secondary me-12">{{building.early_termination_fee_default_cart == 'amenities' ? 'Amenities(default)' : 'Additional Rent'}}</div>
                  <div class="form-check form-switch p-0">
                    <label class="switch mb-0">
                      <input type="checkbox" v-model="building.early_termination_fee_default_cart" role="switch" @change="saveBuildingSettings" />
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="text-medium-bold">Termination/Discount Recovery Fee Grace Period (days)</div>
              <div>
                <div class="d-flex">
                  <input type="number" class="form-control"  v-model="building.termination_fee_days" @change="saveBuildingSettings" />
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="text-medium-bold">Downgrade</div>
              <div>
                <div class="d-flex mt-11">
                  <div class="text-medium color-secondary me-12">{{building.downgrade ? 'On' : 'Off'}}</div>
                  <div class="form-check form-switch p-0">
                    <label class="switch mb-0">
                      <input type="checkbox" v-model="building.downgrade" role="switch" @change="saveBuildingSettings" />
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="text-medium-bold">Rent Term Discount	</div>
              <div>
                <div class="d-flex">
                  <div class="d-flex">
                    <input type="number" class="form-control"  v-model="building.additional_rent_term_discount" @change="saveBuildingSettings" />
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="text-medium-bold">Amenities Term Discount</div>
              <div>
                <div class="d-flex">
                  <div class="d-flex">
                    <input type="number" class="form-control"  v-model="building.amenities_term_discount" @change="saveBuildingSettings"/>
                  </div>
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between mb-4 pyb-4">
              <div class="text-medium-bold">Resident Order Form Term (Months)</div>
              <div>
                <div class="d-flex">
                  <div class="d-flex">
                    <select v-model="building.amenities_document_term" @change="saveBuildingSettings" class="form-control">
                      <option :value="month" v-for="month in 11">{{ month }}</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>    
          </div>
        </div>
      </div>
      </div>
    </div>
  </div>
</template>
<script>
import vitalSigns from "./mainMenuDashboard.vue";

export default {
  components : {
    vitalSigns
  },
  created() {
    // this.loadData();
    this.loadAlertSettings(1, 'Entry Notification');
    this.fetchBuildings();
    this.fetchLinkedBuildings();
  },
  computed: {
    building_id() {
      return this.$store.getters.getBuildingId;
    },
  },
  watch: {
    building_id: function (newBuildingId, oldBuildingId) {
      // this.loadData();
    }
  },
  data() {
    return {
      showBuildingSettings : false,
      alertSettingData : [],
      activeAlertKind : 1,
      activeAlertName : '',
      building : '',
      parkingBuildings : [],
      storageBuildings : [],
      selectedParkingBuilding : [],
      selectedStorageBuilding : [],

      form : new Form({
        linkedBuildings : []
      })
    };
  },
  methods: {
    saveLinkedBuildings(){
      this.showLoader();

      let linkedBuildings = this.selectedParkingBuilding.concat(this.selectedStorageBuilding);
      this.form.linkedBuildings = linkedBuildings;

      this.form
        .post("/api/saveLinkedBuildings/"+this.building.id)
        .then(({ data }) => {
          this.removeLoader();
          Toast.fire({ icon: "success", title: "Changes saved successfully." });
        })
        .catch((error) => {
          this.removeLoader();
        });
    },
    fetchLinkedBuildings() {
      this.$http
          .get("/getLinkedBuildings/"+this.building_id)
          .then((response) => {
            let data = response.data;
            this.selectedParkingBuilding = data.parking;
            this.selectedStorageBuilding = data.storage;
          })
          .catch((error) => {
            console.error(error);
          });
    },
    loadAlertSettings(kind, alertName){
      this.activeAlertKind = kind;
      this.activeAlertName = alertName;
      this.showLoader();
      this.$http
          .get('buildingAlertSettings/'+this.building_id+'/'+kind)
          .then((response) => {
            this.alertSettingData = response.data;
            if(this.alertSettingData && this.alertSettingData.unit_users){

              const index = this.alertSettingData.unit_users.findIndex(record => record.id === this.$gate.user.id);
              if (index !== -1) {
                const recordToMove = this.alertSettingData.unit_users.splice(index, 1)[0];
                this.alertSettingData.unit_users.unshift(recordToMove);
              }
            }
            this.loadBuildingDetails();
            this.removeLoader();
          })
          .catch((error) => {
            console.error(error);
          });
    },
    saveAlertSettings(){
      this.showLoader();
      this.$http
          .post('saveAlertSettings/'+this.$gate.building_id+"/"+this.activeAlertKind,this.alertSettingData)
          .then((response) => {
            Toast.fire({ icon: "success", title: "Alert settings saved successfully." });
            this.removeLoader();
          })
          .catch((error) => {

            this.removeLoader();
            console.error(error);
          });
    },
    loadBuildingDetails(){
      this.$http
          .get('onlyLoadBuildingDetails/'+this.$gate.building_id)
          .then((response) => {
            this.building = response.data.building;
          })
          .catch((error) => {
            console.error(error);
          });
    },
    saveFobSettings(){
      this.showLoader();
      this.$http
          .post('saveFobSettings/'+this.$gate.building_id,this.building)
          .then((response) => {
            Toast.fire({ icon: "success", title: "Fob settings saved successfully." });
            this.removeLoader();
          })
          .catch((error) => {

            this.removeLoader();
            console.error(error);
          });
    },
    saveBuildingSettings(value){
      console.log(value,'value')
      this.$nextTick(() => {
        console.log(this.building.has_pending_task,'value1')
        });
      this.showLoader();
      this.$http
          .post('saveBuildingSettings/'+this.$gate.building_id,this.building)
          .then((response) => {
            Toast.fire({ icon: "success", title: "Settings saved successfully." });
            this.removeLoader();
          })
          .catch((error) => {

            this.removeLoader();
            console.error(error);
          });
    },
    fetchBuildings() {
      this.$http
          .get("/buildings")
          .then((response) => {
            let buildings = response.data.data;
            
            let parkingBuildings = buildings.filter(building => building.type == 'Parking');

            for (const obj of parkingBuildings) {
              const { id, name } = obj;
              this.parkingBuildings.push({ id, name });
            }
            let storageBuildings = buildings.filter(building => building.type == 'Storage');
            for (const obj of storageBuildings) {
              const { id, name } = obj;
              this.storageBuildings.push({ id, name });
            }
          })
          .catch((error) => {
            console.error(error);
          });
    },

  },
};
</script>
<style scoped>
  .link-building-select{
    background-image : url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
  }
</style>

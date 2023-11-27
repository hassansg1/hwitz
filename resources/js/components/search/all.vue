<template>
  <div class="search-section">
    <div class="row mb-50">
      <div class="col-12">
        <div class="d-flex justify-content-between">
          <h3 class="h3 mb-0">
            Result "{{ keyword }}"
            <span class="ms-16 count"
            ><small>{{ this.allData.totalCount }}</small></span
            >
          </h3>

          <div
              class="align-self-center text-normal-bold-md me-10 color-secondary"
          >
            Ordered by:

            <span>
              <select class="mb-3 color-primary p-2" v-model="sortOrder" style="border: none" @change="sort">
                <option class="color-dark" value="asc">Ascending</option>
                <option class="color-dark" value="desc">Descending</option>
                <!-- <option class="color-dark" value="3" selected>
                  Relenvence
                </option> -->
              </select>
            </span>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-3 multiselect-border" v-if="this.$parent.category === 'users'">
          <div class="dropdown-wrapper">
            <label class="dropdown-label text-capitalize"
            >Select User Type</label
            >
            <multiselect
                :selectLabel="''"
                :deselectLabel="''"
                v-model="usertypeIndex"
                placeholder=""
                label="name"
                track-by="id"
                :options="this.usertypes"
                :multiple="false"
                @input="loadData()"
            ></multiselect>
          </div>
        </div>
        <div class="col-lg-3 multiselect-border"
             v-if="this.buildings && (this.$parent.category === 'users' || this.$parent.category === 'documents')">
          <div class="dropdown-wrapper">
            <label class="dropdown-label text-capitalize"
            >Select Building(all)</label
            >
            <multiselect
                :selectLabel="''"
                :deselectLabel="''"
                v-model="buildingIndex"
                placeholder=""
                label="name"
                track-by="id"
                :options="this.buildings"
                :multiple="false"
                @input="changeBuilding()"
            ></multiselect>
          </div>
        </div>
        <div class="col-lg-3 multiselect-border"
             v-if="(this.$parent.category === 'users' || this.$parent.category === 'documents')">

          <div class="dropdown-wrapper">
            <label class="dropdown-label text-capitalize"
            >Select Unit(all)</label
            >
            <multiselect
                :selectLabel="''"
                :deselectLabel="''"
                v-model="unitIndex"
                placeholder=""
                label="unit_no"
                track-by="id"
                :options="this.units"
                :multiple="false"
                @input="changeUnit()"
            ></multiselect>
          </div>
        </div>
        <div class="col-lg-3 multiselect-border"
             v-if="(this.$parent.category === 'documents')">

          <div class="dropdown-wrapper">
            <label class="dropdown-label text-capitalize"
            >Select User(all)</label
            >
            <multiselect
                :selectLabel="''"
                :deselectLabel="''"
                v-model="userIndex"
                placeholder=""
                label="full_name"
                track-by="id"
                :options="this.users"
                :multiple="false"
                @input="loadData()"
            ></multiselect>
          </div>
        </div>
        <div class="col-lg-3 multiselect-border"
             v-if="(this.$parent.category === 'chats')">

          <div class="dropdown-wrapper">
            <label class="dropdown-label text-capitalize"
            >Select From User(all)</label
            >
            <multiselect
                :selectLabel="''"
                :deselectLabel="''"
                v-model="fromUserIndex"
                placeholder=""
                label="full_name"
                track-by="id"
                :options="this.users"
                :multiple="false"
                @input="loadData()"
            ></multiselect>
          </div>
        </div>
        <div class="col-lg-3 multiselect-border"
             v-if="(this.$parent.category === 'chats')">

          <div class="dropdown-wrapper">
            <label class="dropdown-label text-capitalize"
            >Select To User(all)</label
            >
            <multiselect
                :selectLabel="''"
                :deselectLabel="''"
                v-model="toUserIndex"
                placeholder=""
                label="full_name"
                track-by="id"
                :options="this.users"
                :multiple="false"
                @input="loadData()"
            ></multiselect>
          </div>
        </div>
      </div>

      <div v-if="this.category === 'users'" class="col-md-12 mt-2">
      </div>
    </div>
    <div class="row mb-40">
      <div class="col-12">
        <div class="table-responsive" style="overflow-x: hidden;">
          <table class="border-0">
            <div class="row">

              <div class="col-md-6" v-if="allData.users && allData.users.data">
                <h3 class="color-secondary">Users</h3>
                <tr v-if="allData.users" v-for="item in allData.users.data">
                  <td class="align-middle w-87">
                    <div class="d-inline-block mx-auto mx-lg-0">
                      <div class="position-relative text-center">
                        <div class="position-relative avatar-sm overflow-visible">
                          <img
                              v-if="item.profile_picture"
                              :src="item.profile_picture"
                              class="avatar-img"
                          />
                          <img
                              v-else
                              :src="'/images/avatar.png'"
                              class="avatar-img"
                          />
                          <span class="verifiyIcon" v-if="item.email_verified == 1">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="10"
                                height="10"
                                viewBox="0 0 38 38"
                                fill="none"
                            >
                              <circle cx="19" cy="19" r="19" fill="#C2FF69"/>
                              <path
                                  fill-rule="evenodd"
                                  clip-rule="evenodd"
                                  d="M24.9228 14.761C25.2285 15.0455 25.2367 15.5148 24.9413 15.8091L17.5005 23.2214C17.3536 23.3677 17.1507 23.4495 16.9393 23.4474C16.728 23.4454 16.5268 23.3598 16.383 23.2106L13.0475 19.7515C12.7582 19.4516 12.7762 18.9826 13.0878 18.704C13.3993 18.4255 13.8863 18.4428 14.1756 18.7428L16.9583 21.6286L23.8344 14.7788C24.1299 14.4845 24.6172 14.4765 24.9228 14.761Z"
                                  fill="black"
                              />
                            </svg>
                          </span>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div>
                      <h4 class="h4" v-html="matchName(item.name)"></h4>
                      <p class="p" v-html="matchName(item.email)"></p>
                      <p class="p" v-html="matchName(item.mobile)"></p>
                      <p class="p" v-html="matchName(item.user_type_name)"></p>
                      <p
                          class="p"
                          v-if="item.building_name"
                          v-html="
                          matchName(
                            item.building_name +
                              ' , ' +
                              item.building_city +
                              ' , ' +
                              item.unit_no
                          )
                        "
                      ></p>
                    </div>
                  </td>
                  <td class="align-middle td-w-100">
                    <div class="d-flex">
                      <img src="/images/icons/share.png" class="me-10 select-cursor" alt=""
                           @click="openUnitSummary(item)"/>
                      <!-- <img src="/images/icons/document.png" class="me-10" alt="" @click="copyLink(item)" /> -->
                    </div>
                  </td>
                </tr>
                <div class="m-4" v-if="category == 'all'">
                  <pagination :pagination="usersPagination" :type="'users'"></pagination>
                </div>
              </div>

              <div class="col-md-6" v-if="allData.documents && allData.documents.data">
                <h3 class="color-secondary">Documents</h3>
                <tr
                    v-if="allData.documents"
                    v-for="item in allData.documents.data"
                >
                  <td class="align-middle w-87">
                    <div class="icon-container">
                      <img
                          src="/images/icons/attachment-lg.png"
                          class="icon-img"
                          alt=""
                      />
                    </div>
                  </td>
                  <td>
                    <div>
                      <h4
                          v-if="item.packet"
                          class="h4"
                          v-html="matchName(item.packet.packet_name)"
                      ></h4>
                      <p
                          v-if="item.sign_user"
                          class="p"
                          v-html="matchName(item.sign_user.name)"
                      ></p>
                    </div>
                  </td>
                  <td class="align-middle td-w-100">
                    <div class="d-flex">
                      <!-- <img src="/images/icons/share.png" class="me-10" alt="" />
                      <img
                        src="/images/icons/document.png"
                        class="me-10"
                        alt=""
                      /> -->
                    </div>
                  </td>
                </tr>
                <div class="m-4" v-if="category == 'all'">
                  <pagination :pagination="documentsPagination" :type="'documents'"></pagination>
                </div>
              </div>

              <div class="col-md-6" v-if="allData.chats && allData.chats.data">
                <h3 class="color-secondary">Chats</h3>
                <tr v-if="allData.chats" v-for="item in allData.chats.data">
                  <td class="align-middle w-87">
                    <div class="icon-container">
                      <img
                          src="/images/icons/chats.png"
                          class="icon-img"
                          alt=""
                      />
                    </div>
                  </td>
                  <td>
                    <div>
                      <h4 class="h4" v-html="matchName(item.subject)"></h4>
                      <p class="p" v-html="matchName(item.body)"></p>
                      <p class="p" v-html="matchName(item.user.name)"></p>
                    </div>
                  </td>
                  <td class="td-w-100"></td>
                </tr>
                <div class="m-4" v-if="category == 'all'">
                  <pagination :pagination="chatsPagination" :type="'chats'"></pagination>
                </div>
              </div>
            </div>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import config from "../../config";

export default {
  props: ["data", "keyword", "usertypes", "category"],
  data() {
    return {
      allData: {},
      usertypeIndex: "",
      sortOrder: "asc",
      usersPagination: '',
      chatsPagination: '',
      documentsPagination: '',
      buildingIndex: '',
      buildings: [],
      unitIndex: '',
      units: [],
      userIndex: '',
      users: [],
      fromUserIndex: '',
      toUserIndex: '',
    };
  },
  created() {
    this.getBuildings();
    this.getUsers();
  },
  methods: {
    changeUnit() {
      this.loadData();
    },
    changeBuilding() {
      this.unitIndex = "";
      this.units = this.buildingIndex.units;
      this.loadData();
    },
    openUnitSummary(item) {
      // unit-detail/400
      if (item.unit_id_uit) {
        let configuration = config.config;
        Swal.fire({
          title: configuration.confirmBoxTitle,
          text: configuration.switchBuildingConfirmText,
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: configuration.confirmButtonColor,
          cancelButtonColor: configuration.cancelButtonColor,
          confirmButtonText: configuration.confirmButtonText,
        }).then((result) => {
          if (result.value) {
            this.$router.push('unit-detail/' + item.unit_id_uit + '/' + item.building_id);
          }
        });

      }
    },
    getBuildings() {
      this.$http
          .get("/buildings")
          .then((response) => {
            this.buildings = response.data.data;
            this.removeLoader();
          })
          .catch((error) => {
            console.error(error);
          });
    },
    getUsers() {
      this.$http
          .get("/getUsers")
          .then((response) => {
            this.users = response.data.data;
            this.removeLoader();
          })
          .catch((error) => {
            console.error(error);
          });
    },
    loadData(pageNumber = null) {
      this.$parent.refreshData(pageNumber);
    },
    sort() {
      this.$parent.sortColumn = 'created';
      this.$parent.sortOrder = this.sortOrder;
      this.$parent.refreshData();
    },
    selectusertype(index) {
      this.usertypeIndex = index;
      this.$parent.usertypeid = index;
      this.$parent.refreshData();
    },
    refreshData(data) {
      this.allData = data;
    },
    matchName(current) {
      let reggie = new RegExp(this.keyword, "ig");
      if (current) {
        let found = current.search(reggie) !== -1;
        return !found
            ? current
            : current.replace(
                reggie,
                '<b style="background-color: #fde293">' + this.keyword + "</b>"
            );
      }
    },
  },
};
</script>

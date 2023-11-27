<template>
    <div>
      <div
        class="modal fade"
        id="seeReservation"
        tabindex="-1"
        aria-labelledby="makeReservationModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header border-0">
              <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <div class="modal-body text-start">
              <div class="d-flex mb-20">
                <div class="me-16">
                  <!-- <div class="icon-lg">
                    <img src="/images/icons/pencil.png" class="icon-img" alt="" />
                  </div> -->
                </div>
  
                <div>
                  <p class="h3 mb-4">User Reservation(s) - {{ machine.name }}</p>
                  <!-- <p class="text-sm color-secondary">
                    Book the laundry machine so it appears as unavailable to other
                    users
                  </p> -->
                </div>
              </div>
              <!-- <div id="scheduleReservation" class="mb-20 overflow-hidden"></div> -->

              <div class="row pb-24 gy-3">

                  <div class="mb-24 col-xl-12 col-lg-12 col-md-12">
                    <div class="control-group table_new_cost">
                      <div class="controls">
                        <div class="weekly-planner clearfix">
                          <div class="flex" id="weekly_planner_container_html" v-html="weeklyPlannerHtml"></div>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>

              <!-- <div class="d-flex justify-content-end">
                <button class="btn btn-dark">Schedule reservation</button>
              </div> -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data: function () {
      return {
        machine : '',
        weeklyPlannerHtml : ''
      };
    },
    mounted() {
      const script0 = document.createElement("script");
      script0.setAttribute(
        "src",
        "/public/jquery-schedule/dist/jquery.schedule.min.js"
      ); // Replace 'path/to/jquery-schedule.js' with the correct path to the script
      script0.async = true;
  
      // Wait for the script to load before initializing jquery-schedule
      script0.onload = () => {
        // $("#schedule").jqs();
        // Full options
        $("#scheduleReservation").jqs({
          mode: "read",
          hour: 24,
          days: 7,
          periodDuration: 30,
          data: [],
          periodOptions: true,
          periodColors: [],
          periodTitle: "",
          periodBackgroundColor: "rgba(82, 155, 255, 0.5)",
          periodBorderColor: "#2a3cff",
          periodTextColor: "#000",
          periodRemoveButton: "Remove",
          periodDuplicateButton: "Duplicate",
          periodTitlePlaceholder: "Title",
          daysList: [
            "Monday",
            "Tuesday",
            "Wednesday",
            "Thursday",
            "Friday",
            "Saturday",
            "Sunday",
          ],
          onInit: function () {},
          onAddPeriod: function () {},
          onRemovePeriod: function () {},
          onDuplicatePeriod: function () {},
          onClickPeriod: function () {},
        });
  
        console.log("jquery-schedule initialized");
      };
      document.head.appendChild(script0);
  
      console.log(script0);
    },
    methods :{
      loadData(id){
        this.showLoader();
        this.$http.get("/seeReservation/"+id+"/W")
            .then((response) => {
              console.log(response);
              this.weeklyPlannerHtml = response.data;
              this.$nextTick(() => {
                $('[data-toggle="tooltip"]').tooltip({html: true});
              });
              this.removeLoader();
            })
            .catch((error) => {
              console.error(error);
              this.removeLoader();
            });
      }
    }
  };
  </script>
  
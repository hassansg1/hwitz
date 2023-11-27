<template>
  <div class="work-order-modal">
    <div
      class="modal fade"
      :id="'workOrderModal' + workOrder.id"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
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
            <div class="row">
              <div class="col-lg-8">
                <div class="d-flex mb-37">
                  <div>
                    <h3 class="h3 me-47">
                      WO # {{ workOrder.id }} - {{ workOrder.subject }}
                    </h3>
                  </div>
                </div>

                <div class="mb-34">
                  <p class="text-normal-bold-md mb-12 color-secondary">
                    Instructions
                  </p>
                  <textarea
                    name=""
                    id=""
                    rows="5"
                    disabled
                    v-html="workOrder.description"
                  ></textarea>
                </div>

                <div
                  class="mb-34"
                  v-if="workOrder.comments && workOrder.comments.length > 0"
                >
                  <p class="text-normal-bold-md mb-12 color-secondary">
                    Comments
                  </p>
                  <div
                    class="row gap-3 text-wrapper mb-6"
                    v-for="comment in workOrder.comments"
                  >
                    <div class="col-lg-7">
                      <b>{{
                        comment.created_by
                          ? comment.created_by.name
                          : "Deleted User"
                      }}</b>
                      : {{ comment.description }}
                    </div>
                    <div class="col-lg-4">
                      <span
                        class="text-end mb-0 text-normal-bold-md"
                        style="float: right"
                        >{{ formatDateTime(comment.created) }}</span
                      >
                    </div>
                  </div>
                </div>
                <div class="mb-34">
                  <p class="text-normal-bold-md mb-12 color-secondary">
                    Attachment
                  </p>
                  <div class="d-flex gap-3">
                    <form
                      @submit.prevent="addAttachments"
                      id="attachmentForm"
                      @keydown="attachmentForm.onKeydown($event)"
                      enctype="multipart/form-data"
                    >
                      <div
                        class="d-flex align-items-center text-center btn-attachment"
                      >
                        <label
                          class="mx-auto text-center text-white btn-attachment-icon"
                        >
                          +
                          <input
                            type="file"
                            :class="'attachment-input' + workOrder.id"
                            @change="addAttachments"
                            multiple
                          />
                        </label>
                      </div>
                    </form>

                    <div class="d-flex flex-row flex-wrap">
                      <div v-for="(image,key) in workOrder.images" class="me-20 mb-12" style="text-align: -webkit-center;">
                        <div  class="attachment-img-preview">
                          <img :src="image" class="img-fluid attachment-img" alt="" />
                        </div>
                        <span class="text-normal color-secondary">{{ isNumber(key) ? '' : formatDateTime(key)  }}</span>
                      </div>
                    </div>
                  </div>
                </div>


                <div class="row gx-5">
                  <p class="text-normal-bold-md mb-12 color-secondary">
                    Add a comment
                  </p>
                  <form
                    @submit.prevent="addComment"
                    id="attachmentForm"
                    @keydown="commentForm.onKeydown($event)"
                    enctype="multipart/form-data"
                  >
                    <alert-error :form="commentForm"></alert-error>
                    <div class="col-lg-8">
                      <div class="d-flex gap-3">
                        <div class="avatar-sm min-width-39">
                          <img
                            :src="
                              $gate.user.profile_picture
                                ? $gate.user.profile_picture
                                : '/images/default-user.jpg'
                            "
                            class="avatar-img"
                            alt="avatar"
                          />
                        </div>

                        <div class="position-relative w-100">
                          <textarea
                            class="w-100"
                            rows="5"
                            v-model="commentForm.comment"
                          ></textarea>
                          <div class="position-absolute attachment-section">
                            <!-- <img src="/images/attachment.png" class="me-7" alt="" /> -->
                            <!-- <img src="/images/emoji.png" class="me-7" alt="" /> -->
                          </div>

                          <has-error :form="commentForm" field="users"></has-error>
                        </div>
                      </div>
                    </div>
                    <div
                      class="col-lg-4 align-self-end justify-content-end text-md-start text-end mt-md-0 mt-2"
                    >
                      <button type="submit" class="btn btn-dark btn-width">
                        Comment
                      </button>
                    </div>
                  </form>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="mb-34">
                  <div class="row">
                    <div class="col-lg-3 mb-9">
                      <span class="text-wrapper text-normal-bold-md select-cursor" style="padding: 15px !important;border: 1px solid lightskyblue" @click="openEmailPopup(workOrder.id)">Email</span>
                    </div>
                    <div class="col-lg-4 mb-9">
                      <span class="text-wrapper text-normal-bold-md select-cursor" style="padding: 15px !important;border: 1px solid lightskyblue" @click="downloadWorkOrderPDF(workOrder.id)">Save as PDF</span>
                    </div>
                    <div class="col-lg-4 mb-9" >
                      <span class="text-wrapper text-normal-bold-md select-cursor" style="padding: 15px !important;border: 1px solid lightskyblue" @click="downloadWorkOrderPDF(workOrder.id)">Print</span>
                    </div>
                  </div>
                </div>


                <p class="text-normal-bold-md color-secondary-dark mb-10" v-if="workOrder.status_id == 'Close'">
                  Archived At
                </p>
                <div class="text-wrapper mb-22">
                  <p class="text-medium-lg mb-0">
                    {{ workOrder.log ? formatDateStrict(workOrder.log.closed_date) : '-' }}
                  </p>
                </div>

                <p class="text-normal-bold-md color-secondary-dark mb-10">
                  Created by
                </p>
                <div class="person-wrapper mb-22">
                  <div class="d-flex">
                    <div class="d-flex me-27 mb-9">
                      <div class="avatar-sm me-8">
                        <img
                          :src="
                            workOrder.created_by &&
                            workOrder.created_by.profile_picture !== null
                              ? workOrder.created_by.profile_picture
                              : '/images/default-user.jpg'
                          "
                          class="avatar-img"
                          alt=""
                        />
                      </div>
                      <span class="align-self-center text-medium-bold">
                        {{
                          workOrder.created_by ? workOrder.created_by.name : "-"
                        }}
                      </span>
                    </div>
                  </div>
                </div>

                <div class="mb-34">
                  <div class="row">
                    <div class="col-lg-6 mb-9">
                      <p class="text-normal-bold-md color-secondary-dark mb-10">
                        Date created
                      </p>
                      <div class="text-wrapper">
                        <p class="text-medium-lg mb-0">
                          {{ formatDate(workOrder.created) }}
                        </p>
                      </div>
                    </div>

                    <div class="col-lg-6 mb-9">
                      <p class="text-normal-bold-md color-secondary-dark mb-10">
                        Expiry date
                      </p>
                      <div class="text-wrapper">
                        <p class="text-medium-lg mb-0">
                          {{
                            addDaysToDate(
                              workOrder.created,
                              workOrder.expire_days
                            )
                          }}
                        </p>
                      </div>
                    </div>
                  </div>
                </div>

                <p class="text-normal-bold-md color-secondary-dark mb-10">
                  Assignee
                </p>
                <div class="person-wrapper mb-22">
                  <div class="d-flex flex-wrap">
                    <div class="d-flex me-27 mb-9">
                      <div class="avatar-sm me-8">
                        <img
                          :src="
                            workOrder.maintainer &&
                            workOrder.maintainer.profile_picture !== null
                              ? workOrder.maintainer.profile_picture
                              : '/images/default-user.jpg'
                          "
                          class="avatar-img"
                          alt=""
                        />
                      </div>
                      <span class="align-self-center text-medium-bold">
                        {{
                          workOrder.maintainer ? workOrder.maintainer.name : "-"
                        }}
                      </span>
                    </div>
                  </div>
                </div>

                <div class="mb-34">
                  <div class="row">
                    <div class="col-lg-6 mb-9">
                      <p class="text-normal-bold-md color-secondary-dark mb-10">
                        Building
                      </p>
                      <div class="text-wrapper">
                        <p class="text-medium-lg mb-0">
                          {{ workOrder.building_name }}
                        </p>
                      </div>
                    </div>
                    <div class="col-lg-6 mb-9">
                      <p class="text-normal-bold-md color-secondary-dark mb-10">
                        Unit
                      </p>
                      <div class="text-wrapper">
                        <p class="text-medium-lg mb-0">
                          {{ workOrder.unit_no }}
                        </p>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="mb-34">
                  <div class="row">
                    <div class="col-lg-12 mb-9">
                      <p class="text-normal-bold-md color-secondary-dark mb-10">
                        What time you will be available?
                      </p>
                      <div class="row mb-4" v-if="workOrder.time_available" v-for="(time,day) in workOrder.time_available">
                        <div class="col-lg-3">
                          <span class="color-secondary">{{capitalizeFirstLetter(day)}}:</span>
                        </div>
                        <div class="col-lg-3">
                          <span class="color-secondary">{{time.from ? time.from : '-'}}</span>
                        </div>
                        <div class="col-lg-3 text-center">
                          <span class="color-secondary">To</span>
                        </div>
                        <div class="col-lg-3">
                          <span class="color-secondary">{{time.to ? time.to : '-'}}</span>
                        </div>
                      </div>
                      <div class="color-secondary" v-else>-</div>
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
import VuePdfEmbed from "vue-pdf-embed/dist/vue2-pdf-embed";

import DropdownHeader from "../utils/dropdown-header.vue";
export default {
  components: {
    VuePdfEmbed,
    DropdownHeader,
  },
  props: ["workOrder"],
  data() {
    return {
      source1: "/docs/pdf-test.pdf",
      workOptions: [
        {
          id: 1,
          type: "Created",
          isSelected: true,
        },
        {
          id: 2,
          type: "Working",
          isSelected: false,
        },
        {
          id: 3,
          type: "Stuck",
          isSelected: false,
        },
        {
          id: 4,
          type: "Completed",
          isSelected: false,
        },
      ],
      dataArray: [
        { id: 1, name: "john doe", image: "/images/worker.png" },
        { id: 2, name: "jane doe", image: "/images/Ellipse1.png" },
        { id: 3, name: "jane doe 2", image: "/images/Ellipse14.png" },

        { id: 4, name: "john doe", image: "/images/worker.png" },
        { id: 5, name: "jane doe", image: "/images/Ellipse14.png" },
        { id: 6, name: "jane doe 2", image: "/images/Ellipse1.png" },
      ],

      newdataArray: [
        { id: 1, value: "12.04.2022" },
        { id: 2, value: "12.05.2022" },
        { id: 3, value: "12.06.2022" },
        { id: 4, value: "12.07.2022" },
        { id: 5, value: "12.08.2022" },
        { id: 6, value: "12.09.2022" },
      ],
      building: [
        { id: 1, value: "128 A Pine" },
        { id: 2, value: "128 B Pine" },
        { id: 3, value: "128 C Pine" },
        { id: 4, value: "128 D Pine" },
        { id: 5, value: "128 E Pine" },
        { id: 6, value: "128 F Pine" },
      ],
      unit: [
        { id: 1, value: "Block A" },
        { id: 2, value: "Block B" },
        { id: 3, value: "Block C" },
        { id: 4, value: "Block D" },
        { id: 5, value: "Block E" },
        { id: 6, value: "Block F" },
      ],
      attachmentForm: new Form({
        files: [],
      }),
      commentForm: new Form({
        comment: "",
      }),
    };
  },
  methods: {
    openEmailPopup(id){
      $('#workOrderEmailModal'+id).modal('show');
    },
    downloadWorkOrderPDF(id){
      window.open('/downloadWorkOrderPDF/'+id, '_blank');
      return;
      this.showLoader();
      this.$http
        .post("/workorder/downloadWorkOrderPDF/" +id)
        .then((response) => {
          console.log(response,'response');
          this.removeLoader();
        })
        .catch((error) => {
          console.error(error);
          this.removeLoader();
        });
    },
    addAttachments() {
      this.showLoader();
      this.attachmentForm.files = [];
      var files = $(".attachment-input" + this.workOrder.id)[0].files;
      for (var i = 0; i < files.length; i++) {
        var fileup = files[i];
        this.attachmentForm.files.push(fileup);
      }
      this.attachmentForm
        .post("/api/addAttachment/" + this.workOrder.id)
        .then(({ data }) => {
          if (this.workOrder.status_id == "Close")
            this.$parent.loadData(this.$parent.pageNumber);
          else this.$parent.loadData(this.$parent.pageNumber, this.$parent.selectedBuilding);
          this.attachmentForm.reset();
          this.removeLoader();
        })
        .catch((error) => {
          this.removeLoader();
        });
    },
    addComment() {
      this.showLoader();
      this.commentForm
        .post("/api/addComment/" + this.workOrder.id)
        .then(({ data }) => {
          if (this.workOrder.status_id == "Close")
            this.$parent.loadData(this.$parent.pageNumber);
          else this.$parent.loadData(this.$parent.pageNumber,this.$parent.selectedBuilding);
          this.commentForm.reset();
          this.removeLoader();
        })
        .catch((error) => {
          this.removeLoader();
        });
    },
  },
};
</script>

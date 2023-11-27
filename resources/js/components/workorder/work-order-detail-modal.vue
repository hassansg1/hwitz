<template>
  <div>
    <div
      class="modal fade work-order-detail-modal"
      id="workOrderDetail"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-xl modal-customize">
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
                  <div class="align-self-center">
                    <h3 class="h3 mb-0 me-16">Windows cleaning</h3>
                  </div>

                  <!-- <div class="mb-8">
                    <div
                      id="dropdownMenuOffset"
                      data-bs-toggle="dropdown"
                      aria-expanded="false"
                    >
                      <span v-for="option of workOptions">
                        <span
                          v-if="option.isSelected === true"
                          class="dropdown-item"
                          :class="[
                            { 'bkg-danger': option.type === 'Stuck' },
                            {
                              'bkg-success text-dark':
                                option.type === 'Completed',
                            },
                            { 'bkg-primary': option.type === 'Working' },
                            { 'bkg-gray': option.type === 'Created' },
                          ]"
                          >{{ option.type }}</span
                        >
                      </span>
                      <span>
                        <img src="/images/icons/down-arrow.png" alt="icon"
                      /></span>
                    </div>
                    <div class="dropdown">
                      <div
                        class="dropdown-menu dropdown-menu-end"
                        aria-labelledby="dropdownMenuOffset"
                      >
                        <div
                          v-for="option of workOptions"
                          class="mb-9 text-center mx-auto drop-option"
                          v-if="option.isSelected === false"
                          :class="[
                            { 'bkg-danger': option.type === 'Stuck' },
                            {
                              'bkg-success text-dark':
                                option.type === 'Completed',
                            },
                            { 'bkg-primary': option.type === 'Working' },
                            { 'bkg-gray': option.type === 'Created' },
                          ]"
                        >
                          <div>
                            {{ option.type }}
                          </div>
                        </div>
                      </div>
                    </div>
                  </div> -->

                  <div class="align-self-center">
                    <img src="/images/icons/edit.png" alt="" />
                  </div>
                </div>

                <div class="mb-34">
                  <p class="text-normal-bold-md mb-12 color-secondary">
                    Instructions
                  </p>
                  <textarea name="" id="" rows="5"></textarea>
                </div>

                <div class="mb-34">
                  <p class="text-normal-bold-md mb-12 color-secondary">
                    Attachment
                  </p>
                  <div class="d-flex gap-3">
                    <div
                      class="d-flex align-items-center text-center btn-attachment"
                    >
                      <label
                        class="mx-auto text-center text-white btn-attachment-icon"
                      >
                        +
                        <input type="file" />
                      </label>
                    </div>

                    <div>
                      <img
                        src="/images/Rectangle.png"
                        class="img-fluid"
                        alt=""
                      />
                    </div>
                    <div>
                      <img
                        src="/images/Rectangle.png"
                        class="img-fluid"
                        alt=""
                      />
                    </div>
                    <div>
                      <img
                        src="/images/Rectangle.png"
                        class="img-fluid"
                        alt=""
                      />
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="mb-34">
                  <div class="mb-6">
                    <RichMultiSelect
                      :optionsArray="dataArray"
                      :header="'Created by'"
                    />
                  </div>
                </div>

                <div class="mb-34">
                  <div class="row g-5">
                    <div class="col-lg-6">
                      <DropdownHeader
                        :optionsArray="newdataArray"
                        :header="'Date Created'"
                      />
                    </div>
                    <div class="col-lg-6">
                      <DropdownHeader
                        :optionsArray="newdataArray"
                        :header="'Due Date'"
                      />
                    </div>
                  </div>
                </div>

                <div class="mb-34">
                  <div class="mb-34">
                    <div class="mb-6">
                      <RichMultiSelect
                        :optionsArray="dataArray"
                        :header="'Assignee'"
                      />
                    </div>
                  </div>
                </div>

                <div class="mb-34">
                  <div class="row g-5">
                    <div class="col-lg-6">
                      <DropdownHeader
                        :optionsArray="building"
                        :header="'Building'"
                      />
                    </div>
                    <div class="col-lg-6">
                      <DropdownHeader :optionsArray="unit" :header="'Unit'" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row gx-5">
              <div class="col-lg-8">
                <div class="d-flex gap-3">
                  <div>
                    <img
                      src="/images/worker.png"
                      class="avator-sm"
                      alt="avatar"
                    />
                  </div>

                  <div class="position-relative w-100">
                    <textarea class="w-100" rows="5"></textarea>
                    <div class="position-absolute attachment-section">
                      <img src="/images/attachment.png" class="me-7" alt="" />
                      <img src="/images/emoji.png" class="me-7" alt="" />
                    </div>
                  </div>
                </div>
              </div>
              <div
                class="col-lg-4 align-self-end justify-content-end text-md-start text-end mt-md-0 mt-2"
              >
                <button class="btn btn-dark px-4 pb-2">Comment</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import RichMultiSelect from "../utils/rich-multi-select.vue";
import DropdownHeader from "../utils/dropdown-header.vue";
export default {
  components: {
    RichMultiSelect,
    DropdownHeader,
  },
  data() {
    return {
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
    };
  },
};
</script>

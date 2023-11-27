<template>
  <div class="main verification">
    <div class="mb-32">
      <div class="row">
        <div class="col-lg-3 mb-6">
          <div class="dropdown-wrapper">
            <label class="dropdown-label text-capitalize">Building</label>
            <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
              v-model="params.building_id"
              placeholder=""
              label="name"
              track-by="id"
              :options="buildings"
              :multiple="false"
              @input="getResidentsByFilters"
            ></multiselect>
          </div>
        </div>
      </div>
      <div class="d-flex">
        <div>
          <input
            class="form-check-input me-12"
            type="checkbox"
            value=""
            id="flexCheckDefault"
          />
        </div>
        <div class="align-self-center">
          <label class="form-check-label text-normal" for="flexCheckDefault">
            Unverified building users
          </label>
        </div>
      </div>
    </div>
    <div class="row gy-5 gx-5 mb-50">
      <div class="col-lg-4 col-md-6 col-12 mb-">
        <div class="row bkg-secondary color-secondary">
          <div class="col-3"></div>
          <div class="col-9">
            <div class="py-7">Name</div>
          </div>
        </div>

        <div
          class="row border-bottom"
          data-bs-toggle="modal"
          data-bs-target="#verificationModal"
        >
          <div class="col-3">
            <div class="py-7">
              <input
                class="form-check-input me-12"
                type="checkbox"
                value=""
                id="flexCheckDefault"
              />
            </div>
          </div>
          <div class="col-9">
            <div class="d-flex py-7">
              <div class="avatar-sm me-8 align-self-center">
                <img
                  src="/images/worker.png"
                  class="avatar-img"
                  alt="worker"
                />
              </div>
              <div class="align-self-center">
                <p class="text-medium-bold mb-0">Name</p>
                <p class="text-normal mb-0 color-danger">Unverified</p>
              </div>
            </div>
          </div>
        </div>

        <div class="row border-bottom">
          <div class="col-3">
            <div class="py-7">
              <input
                class="form-check-input me-12"
                type="checkbox"
                value=""
                id="flexCheckDefault"
              />
            </div>
          </div>
          <div class="col-9">
            <div class="d-flex py-7">
              <div class="avatar-sm me-8 align-self-center">
                <img
                  src="/images/worker.png"
                  class="avatar-img"
                  alt="worker"
                />
              </div>
              <div class="align-self-center">
                <p class="text-medium-bold mb-0">Name</p>
                <p class="text-normal mb-0 color-primary">123 456 7890</p>
              </div>
            </div>
          </div>
        </div>

        <div class="row border-bottom">
          <div class="col-3">
            <div class="py-7">
              <input
                class="form-check-input me-12"
                type="checkbox"
                value=""
                id="flexCheckDefault"
              />
            </div>
          </div>
          <div class="col-9">
            <div class="d-flex py-7">
              <div class="avatar-sm me-8 align-self-center">
                <img
                  src="/images/worker.png"
                  class="avatar-img"
                  alt="worker"
                />
              </div>
              <div class="align-self-center">
                <p class="text-medium-bold mb-0">Name</p>
                <p class="text-normal mb-0 color-primary">123 456 7890</p>
              </div>
            </div>
          </div>
        </div>

        <div class="row border-bottom">
          <div class="col-3">
            <div class="py-7">
              <input
                class="form-check-input me-12"
                type="checkbox"
                value=""
                id="flexCheckDefault"
              />
            </div>
          </div>
          <div class="col-9">
            <div class="d-flex py-7">
              <div class="avatar-sm me-8 align-self-center">
                <img
                  src="/images/worker.png"
                  class="avatar-img"
                  alt="worker"
                />
              </div>
              <div class="align-self-center">
                <p class="text-medium-bold mb-0">Name</p>
                <p class="text-normal mb-0 color-primary">123 456 7890</p>
              </div>
            </div>
          </div>
        </div>

        <div class="row border-bottom">
          <div class="col-3">
            <div class="py-7">
              <input
                class="form-check-input me-12"
                type="checkbox"
                value=""
                id="flexCheckDefault"
              />
            </div>
          </div>
          <div class="col-9">
            <div class="d-flex py-7">
              <div class="avatar-sm me-8 align-self-center">
                <img
                  src="/images/worker.png"
                  class="avatar-img"
                  alt="worker"
                />
              </div>
              <div class="align-self-center">
                <p class="text-medium-bold mb-0">Name</p>
                <p class="text-normal mb-0 color-primary">123 456 7890</p>
              </div>
            </div>
          </div>
        </div>

        <div class="row border-bottom">
          <div class="col-3">
            <div class="py-7">
              <input
                class="form-check-input me-12"
                type="checkbox"
                value=""
                id="flexCheckDefault"
              />
            </div>
          </div>
          <div class="col-9">
            <div class="d-flex py-7">
              <div class="avatar-sm me-8 align-self-center">
                <img
                  src="/images/worker.png"
                  class="avatar-img"
                  alt="worker"
                />
              </div>
              <div class="align-self-center">
                <p class="text-medium-bold mb-0">Name</p>
                <p class="text-normal mb-0 color-danger">Unverified</p>
              </div>
            </div>
          </div>
        </div>

        <div class="row border-bottom">
          <div class="col-3">
            <div class="py-7">
              <input
                class="form-check-input me-12"
                type="checkbox"
                value=""
                id="flexCheckDefault"
              />
            </div>
          </div>
          <div class="col-9">
            <div class="d-flex py-7">
              <div class="avatar-sm me-8 align-self-center">
                <img
                  src="/images/worker.png"
                  class="avatar-img"
                  alt="worker"
                />
              </div>
              <div class="align-self-center">
                <p class="text-medium-bold mb-0">Name</p>
                <p class="text-normal mb-0 color-primary">123 456 7890</p>
              </div>
            </div>
          </div>
        </div>

        <div class="row border-bottom">
          <div class="col-3">
            <div class="py-7">
              <input
                class="form-check-input me-12"
                type="checkbox"
                value=""
                id="flexCheckDefault"
              />
            </div>
          </div>
          <div class="col-9">
            <div class="d-flex py-7">
              <div class="avatar-sm me-8 align-self-center">
                <img
                  src="/images/worker.png"
                  class="avatar-img"
                  alt="worker"
                />
              </div>
              <div class="align-self-center">
                <p class="text-medium-bold mb-0">Name</p>
                <p class="text-normal mb-0 color-primary">123 456 7890</p>
              </div>
            </div>
          </div>
        </div>

        <div class="row border-bottom">
          <div class="col-3">
            <div class="py-7">
              <input
                class="form-check-input me-12"
                type="checkbox"
                value=""
                id="flexCheckDefault"
              />
            </div>
          </div>
          <div class="col-9">
            <div class="d-flex py-7">
              <div class="avatar-sm me-8 align-self-center">
                <img
                  src="/images/worker.png"
                  class="avatar-img"
                  alt="worker"
                />
              </div>
              <div class="align-self-center">
                <p class="text-medium-bold mb-0">Name</p>
                <p class="text-normal mb-0 color-primary">123 456 7890</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6 col-12 mb-">
        <div class="row bkg-secondary color-secondary">
          <div class="col-3"></div>
          <div class="col-9">
            <div class="py-7">Name</div>
          </div>
        </div>

        <div class="row border-bottom">
          <div class="col-3">
            <div class="py-7">
              <input
                class="form-check-input me-12"
                type="checkbox"
                value=""
                id="flexCheckDefault"
              />
            </div>
          </div>
          <div class="col-9">
            <div class="d-flex py-7">
              <div class="avatar-sm me-8 align-self-center">
                <img
                  src="/images/worker.png"
                  class="avatar-img"
                  alt="worker"
                />
              </div>
              <div class="align-self-center">
                <p class="text-medium-bold mb-0">Name</p>
                <p class="text-normal mb-0 color-primary">123 456 7890</p>
              </div>
            </div>
          </div>
        </div>

        <div class="row border-bottom">
          <div class="col-3">
            <div class="py-7">
              <input
                class="form-check-input me-12"
                type="checkbox"
                value=""
                id="flexCheckDefault"
              />
            </div>
          </div>
          <div class="col-9">
            <div class="d-flex py-7">
              <div class="avatar-sm me-8 align-self-center">
                <img
                  src="/images/worker.png"
                  class="avatar-img"
                  alt="worker"
                />
              </div>
              <div class="align-self-center">
                <p class="text-medium-bold mb-0">Name</p>
                <p class="text-normal mb-0 color-danger">Unverified</p>
              </div>
            </div>
          </div>
        </div>

        <div class="row border-bottom">
          <div class="col-3">
            <div class="py-7">
              <input
                class="form-check-input me-12"
                type="checkbox"
                value=""
                id="flexCheckDefault"
              />
            </div>
          </div>
          <div class="col-9">
            <div class="d-flex py-7">
              <div class="avatar-sm me-8 align-self-center">
                <img
                  src="/images/worker.png"
                  class="avatar-img"
                  alt="worker"
                />
              </div>
              <div class="align-self-center">
                <p class="text-medium-bold mb-0">Name</p>
                <p class="text-normal mb-0 color-danger">Unverified</p>
              </div>
            </div>
          </div>
        </div>

        <div class="row border-bottom">
          <div class="col-3">
            <div class="py-7">
              <input
                class="form-check-input me-12"
                type="checkbox"
                value=""
                id="flexCheckDefault"
              />
            </div>
          </div>
          <div class="col-9">
            <div class="d-flex py-7">
              <div class="avatar-sm me-8 align-self-center">
                <img
                  src="/images/worker.png"
                  class="avatar-img"
                  alt="worker"
                />
              </div>
              <div class="align-self-center">
                <p class="text-medium-bold mb-0">Name</p>
                <p class="text-normal mb-0 color-danger">Unverified</p>
              </div>
            </div>
          </div>
        </div>

        <div class="row border-bottom">
          <div class="col-3">
            <div class="py-7">
              <input
                class="form-check-input me-12"
                type="checkbox"
                value=""
                id="flexCheckDefault"
              />
            </div>
          </div>
          <div class="col-9">
            <div class="d-flex py-7">
              <div class="avatar-sm me-8 align-self-center">
                <img
                  src="/images/worker.png"
                  class="avatar-img"
                  alt="worker"
                />
              </div>
              <div class="align-self-center">
                <p class="text-medium-bold mb-0">Name</p>
                <p class="text-normal mb-0 color-danger">Unverified</p>
              </div>
            </div>
          </div>
        </div>

        <div class="row border-bottom">
          <div class="col-3">
            <div class="py-7">
              <input
                class="form-check-input me-12"
                type="checkbox"
                value=""
                id="flexCheckDefault"
              />
            </div>
          </div>
          <div class="col-9">
            <div class="d-flex py-7">
              <div class="avatar-sm me-8 align-self-center">
                <img
                  src="/images/worker.png"
                  class="avatar-img"
                  alt="worker"
                />
              </div>
              <div class="align-self-center">
                <p class="text-medium-bold mb-0">Name</p>
                <p class="text-normal mb-0 color-primary">123 456 7890</p>
              </div>
            </div>
          </div>
        </div>

        <div class="row border-bottom">
          <div class="col-3">
            <div class="py-7">
              <input
                class="form-check-input me-12"
                type="checkbox"
                value=""
                id="flexCheckDefault"
              />
            </div>
          </div>
          <div class="col-9">
            <div class="d-flex py-7">
              <div class="avatar-sm me-8 align-self-center">
                <img
                  src="/images/worker.png"
                  class="avatar-img"
                  alt="worker"
                />
              </div>
              <div class="align-self-center">
                <p class="text-medium-bold mb-0">Name</p>
                <p class="text-normal mb-0 color-danger">Unverified</p>
              </div>
            </div>
          </div>
        </div>

        <div class="row border-bottom">
          <div class="col-3">
            <div class="py-7">
              <input
                class="form-check-input me-12"
                type="checkbox"
                value=""
                id="flexCheckDefault"
              />
            </div>
          </div>
          <div class="col-9">
            <div class="d-flex py-7">
              <div class="avatar-sm me-8 align-self-center">
                <img
                  src="/images/worker.png"
                  class="avatar-img"
                  alt="worker"
                />
              </div>
              <div class="align-self-center">
                <p class="text-medium-bold mb-0">Name</p>
                <p class="text-normal mb-0 color-primary">123 456 7890</p>
              </div>
            </div>
          </div>
        </div>

        <div class="row border-bottom">
          <div class="col-3">
            <div class="py-7">
              <input
                class="form-check-input me-12"
                type="checkbox"
                value=""
                id="flexCheckDefault"
              />
            </div>
          </div>
          <div class="col-9">
            <div class="d-flex py-7">
              <div class="avatar-sm me-8 align-self-center">
                <img
                  src="/images/worker.png"
                  class="avatar-img"
                  alt="worker"
                />
              </div>
              <div class="align-self-center">
                <p class="text-medium-bold mb-0">Name</p>
                <p class="text-normal mb-0 color-danger">Unverified</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6 col-12 mb-">
        <div class="row bkg-secondary color-secondary">
          <div class="col-3"></div>
          <div class="col-9">
            <div class="py-7">Name</div>
          </div>
        </div>

        <div class="row border-bottom">
          <div class="col-3">
            <div class="py-7">
              <input
                class="form-check-input me-12"
                type="checkbox"
                value=""
                id="flexCheckDefault"
              />
            </div>
          </div>
          <div class="col-9">
            <div class="d-flex py-7">
              <div class="avatar-sm me-8 align-self-center">
                <img
                  src="/images/worker.png"
                  class="avatar-img"
                  alt="worker"
                />
              </div>
              <div class="align-self-center">
                <p class="text-medium-bold mb-0">Name</p>
                <p class="text-normal mb-0 color-danger">Unverified</p>
              </div>
            </div>
          </div>
        </div>

        <div class="row border-bottom">
          <div class="col-3">
            <div class="py-7">
              <input
                class="form-check-input me-12"
                type="checkbox"
                value=""
                id="flexCheckDefault"
              />
            </div>
          </div>
          <div class="col-9">
            <div class="d-flex py-7">
              <div class="avatar-sm me-8 align-self-center">
                <img
                  src="/images/worker.png"
                  class="avatar-img"
                  alt="worker"
                />
              </div>
              <div class="align-self-center">
                <p class="text-medium-bold mb-0">Name</p>
                <p class="text-normal mb-0 color-danger">Unverified</p>
              </div>
            </div>
          </div>
        </div>

        <div class="row border-bottom">
          <div class="col-3">
            <div class="py-7">
              <input
                class="form-check-input me-12"
                type="checkbox"
                value=""
                id="flexCheckDefault"
              />
            </div>
          </div>
          <div class="col-9">
            <div class="d-flex py-7">
              <div class="avatar-sm me-8 align-self-center">
                <img
                  src="/images/worker.png"
                  class="avatar-img"
                  alt="worker"
                />
              </div>
              <div class="align-self-center">
                <p class="text-medium-bold mb-0">Name</p>
                <p class="text-normal mb-0 color-primary">123 456 7890</p>
              </div>
            </div>
          </div>
        </div>

        <div class="row border-bottom">
          <div class="col-3">
            <div class="py-7">
              <input
                class="form-check-input me-12"
                type="checkbox"
                value=""
                id="flexCheckDefault"
              />
            </div>
          </div>
          <div class="col-9">
            <div class="d-flex py-7">
              <div class="avatar-sm me-8 align-self-center">
                <img
                  src="/images/worker.png"
                  class="avatar-img"
                  alt="worker"
                />
              </div>
              <div class="align-self-center">
                <p class="text-medium-bold mb-0">Name</p>
                <p class="text-normal mb-0 color-primary">123 456 7890</p>
              </div>
            </div>
          </div>
        </div>

        <div class="row border-bottom">
          <div class="col-3">
            <div class="py-7">
              <input
                class="form-check-input me-12"
                type="checkbox"
                value=""
                id="flexCheckDefault"
              />
            </div>
          </div>
          <div class="col-9">
            <div class="d-flex py-7">
              <div class="avatar-sm me-8 align-self-center">
                <img
                  src="/images/worker.png"
                  class="avatar-img"
                  alt="worker"
                />
              </div>
              <div class="align-self-center">
                <p class="text-medium-bold mb-0">Name</p>
                <p class="text-normal mb-0 color-primary">123 456 7890</p>
              </div>
            </div>
          </div>
        </div>

        <div class="row border-bottom">
          <div class="col-3">
            <div class="py-7">
              <input
                class="form-check-input me-12"
                type="checkbox"
                value=""
                id="flexCheckDefault"
              />
            </div>
          </div>
          <div class="col-9">
            <div class="d-flex py-7">
              <div class="avatar-sm me-8 align-self-center">
                <img
                  src="/images/worker.png"
                  class="avatar-img"
                  alt="worker"
                />
              </div>
              <div class="align-self-center">
                <p class="text-medium-bold mb-0">Name</p>
                <p class="text-normal mb-0 color-danger">Unverified</p>
              </div>
            </div>
          </div>
        </div>

        <div class="row border-bottom">
          <div class="col-3">
            <div class="py-7">
              <input
                class="form-check-input me-12"
                type="checkbox"
                value=""
                id="flexCheckDefault"
              />
            </div>
          </div>
          <div class="col-9">
            <div class="d-flex py-7">
              <div class="avatar-sm me-8 align-self-center">
                <img
                  src="/images/worker.png"
                  class="avatar-img"
                  alt="worker"
                />
              </div>
              <div class="align-self-center">
                <p class="text-medium-bold mb-0">Name</p>
                <p class="text-normal mb-0 color-primary">123 456 7890</p>
              </div>
            </div>
          </div>
        </div>

        <div class="row border-bottom">
          <div class="col-3">
            <div class="py-7">
              <input
                class="form-check-input me-12"
                type="checkbox"
                value=""
                id="flexCheckDefault"
              />
            </div>
          </div>
          <div class="col-9">
            <div class="d-flex py-7">
              <div class="avatar-sm me-8 align-self-center">
                <img
                  src="/images/worker.png"
                  class="avatar-img"
                  alt="worker"
                />
              </div>
              <div class="align-self-center">
                <p class="text-medium-bold mb-0">Name</p>
                <p class="text-normal mb-0 color-primary">123 456 7890</p>
              </div>
            </div>
          </div>
        </div>

        <div class="row border-bottom">
          <div class="col-3">
            <div class="py-7">
              <input
                class="form-check-input me-12"
                type="checkbox"
                value=""
                id="flexCheckDefault"
              />
            </div>
          </div>
          <div class="col-9">
            <div class="d-flex py-7">
              <div class="avatar-sm me-8 align-self-center">
                <img
                  src="/images/worker.png"
                  class="avatar-img"
                  alt="worker"
                />
              </div>
              <div class="align-self-center">
                <p class="text-medium-bold mb-0">Name</p>
                <p class="text-normal mb-0 color-primary">123 456 7890</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="d-flex justify-content-end">
      <button class="btn bkg-primary text-white">Send Verificaton</button>
    </div>

    <!-- modal design -->
    <!-- <div class="row">
      <div class="col-lg-6 mx-auto">
        <h1 class="h1 mb-13">Verification Broadcast</h1>
        <p class="mb-28 text-medium color-secondary">
          Lorem ipsum dolor, sit amet consectetur adipisicing elit. Architecto
          repellendus doloribus odio dignissimos expedita rem!
        </p>

        <div class="mb-24">
          <p class="text-normal-bold-md mb-12 color-secondary">
            Select building
          </p>

          <div class="d-flex flex-wrap">
            <span class="custome-badge me-12 mb-12">142 N Addison</span>
            <span class="custome-badge me-12 mb-12 active">142 N Addison</span>

            <span class="custome-badge me-12 mb-12">142 N Addison</span>
            <span class="custome-badge me-12 mb-12 active">142 N Addison</span>

            <span class="custome-badge me-12 mb-12">142 N Addison</span>
            <span class="custome-badge me-12 mb-12 active">142 N Addison</span>
            <span class="custome-badge me-12 mb-12">142 N Addison</span>
            <span class="custome-badge me-12 mb-12 active"
              >142 N Addison sjdhfjs
            </span>
            <span class="custome-badge me-12 mb-12">142 N Addison</span>
            <span class="custome-badge me-12 mb-12 active"
              >142 N Addison dfg</span
            >
            <span class="custome-badge me-12 mb-12">142 N Addison</span>
            <span class="custome-badge me-12 mb-12 active"
              >142 N hhh h hhAddison</span
            >
          </div>
        </div>

        <div class="row mb-24">
          <div class="col-lg-9">
            <label class="text-normal-bold-md mb-12 color-secondary" for="title"
              >Title</label
            >
            <input id="title" type="text" class="form-control" />
          </div>
          <div class="col-lg-3">
            <label class="text-normal-bold-md mb-12 color-secondary" for="date"
              >Date</label
            >
            <input id="date" type="text" class="form-control" />
          </div>
        </div>

        <div class="mb-24">
          <p class="text-normal-bold-md mb-12 color-secondary">Message type</p>
          <div>
            <span class="custome-badge active">Both</span>
          </div>
        </div>

        <div class="mb-24">
          <p class="text-normal-bold-md mb-12 color-secondary">Message</p>
          <div>
            <textarea name="" id="" rows="5"></textarea>
          </div>
        </div>

        <div class="mb-24">
          <p class="text-normal-bold-md mb-12 color-secondary">
            Service de-activation date
          </p>
          <div class="row">
            <div class="col-lg-4">
              <input type="text" class="form-control" />
            </div>
          </div>
        </div>

        <div class="d-flex justify-content-evenly">
          <button class="btn btn-dark">Reset</button>
          <button class="btn btn-primary">Send Verification</button>
        </div>
      </div>
    </div> -->

    <VerificationModal />
  </div>
</template>
<script>
import VerificationModal from "./verification-modal.vue";

export default {
  components: {
    VerificationModal,
  },
  data(){
    return {
      params : {
        usertype_id : 'all',
        template_type: 'v',
        parking: 0,
        tv: 0,
        phone: 0,
        internet: 0,
        package: 0,
        building_id : ''
      },
      buildings : [],
      residents : ''
    };
  },
  methods : {
    getResidentsByFilters(){
      this.$http
        .post("/workorder/getResidentsByFilters",this.params)
        .then((response) => {
          this.residents = response.data && response.data.resident && response.data.resident.length > 0 ? response.data.resident : [];
          this.removeLoader();
        })
        .catch((error) => {
          console.error(error);
        });
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
  }
};
</script>

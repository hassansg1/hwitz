<template>
  <div>
    <div
        class="modal fade"
        id="addNewSystemUser"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
    >
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header border-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body text-start">
            <div class="row">
              <AddStaffUser :parent-page="'manage_users'"></AddStaffUser>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import AddStaffUser from "../staff/add-staff-user.vue";

export default {
  components: {AddStaffUser},
  data() {
    return {
      title: "Add new sub role",
      form: new Form({
        name: '',
        alias: '',
        id: null,
      }),
    };
  },
  methods: {
    loadData() {
      $("#addNewSystemUser").modal("hide");
      this.$parent.loadData();

    },
  },
};
</script>


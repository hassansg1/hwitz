<template>
    <div>
      <div
        class="modal fade task-modal"
        :id="'workOrderPriorityLogsModal'"
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
                <div class="mb-27">
                    <div class="table-responsive mb-28">
                        <table>
                        <tr>
                            <th>Performed By</th>
                            <th>Action</th>
                            <th>Action Date</th>
                            <th>Ip Address</th>
                            <th>User Agent</th>
                        </tr>
                        <tr v-for="log in data" class="border_bottom">
                            <td>
                                <span class="text-medium">
                                    {{ log.modified_by ? log.modified_by.firstname + " " + log.modified_by.lastname : '-' }}
                                </span>
                            </td>
                            <td>
                                Work Order priority changed to {{ log.priority }}
                            </td>
                            <td>
                                {{ formatDateTime(log.created_at)  }}
                            </td>
                            <td>{{ log.ip_address }}</td>
                            <td>{{ log.user_agent }}</td>
                        </tr>
                        </table>
                    </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    components: {
    },
    data() {
        return {
            data : []
        };
    },
    props: [],
  };
  </script>
  
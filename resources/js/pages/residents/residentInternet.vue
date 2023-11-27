<template>
  <div class="mb-64">
    <h3 class="h3 mb-28">Internet access</h3>
    <div class="text-start">
      <div class="table-responsive">
        <table>
          <tr>
            <th>Name</th>
            <th>Data Plan</th>
            <th>Email</th>
            <th>Location</th>
            <th>Usage</th>
            <th>Status</th>
          </tr>

          <tr class="border_bottom" v-for="unit in units">
            <td>
              <div class="text-medium-bold color-primary" v-if="user">{{ user.name }}</div>
            </td>
            <td>
              <span class="text-medium color-secondary">Date Plan</span>
            </td>
            <td>
              <span class="text-medium color-secondary" v-if="user">{{ user.email }}</span>
            </td>
            <td>
                        <span class="text-medium color-secondary">
                          {{ unit.unit_no }},
                          {{ unit.building ? unit.building.name + ',' : '' }}
                          {{ unit.building ? unit.building.city + ',' : '' }}
                          {{ unit.building ? unit.building.state + ',' : '' }}
                          {{ unit.building ? unit.building.country + ',' : '' }}
                          {{ unit.building ? unit.building.zipcode : '' }}
                        </span>
            </td>
            <td>
              <span class="text-medium color-secondary" v-html="unit.formatted_internet_daily"></span><br>
              <span class="text-medium color-secondary" v-html="unit.formatted_internet_usage"></span>
            </td>
            <td>
              <div>
                <div class="d-flex flex-column mt-20">
                  <div class="text-medium text-center color-secondary me-12">
                    {{ unit.is_internet ? 'On' : 'Off' }}
                  </div>
                  <div class="form-check form-switch p-0">
                    <label class="switch mb-0">
                      <input type="checkbox" v-model="unit.is_internet" role="switch"/>
                      <span class="slider round"></span>
                    </label>
                  </div>
                  <div class="text-medium text-center color-secondary me-12">
                    {{ unit.vlan ? unit.vlan : '' }}
                  </div>
                </div>
              </div>
            </td>
          </tr>
          <tr v-if="!units || units.length == 0">
            <td class="text-center" colspan="5">No Data found.</td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  components: {},
  props: ["units", "user"],
  watch: {},
  data() {
    return {};
  },
  computed: {},
  methods: {},
};
</script>
 
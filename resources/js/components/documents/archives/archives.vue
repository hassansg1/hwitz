<template>
  <div class="archive-section">
    <div class="archiveModal">
      <div class="building mb-36">
        <p class="text-normal-bold-lg mb-12 color-secondary">Building</p>
        <div class="d-flex flex-row flex-wrap">
          <div
            class="building-badge"
            :class="{ 'active-badge': index === building }"
            v-for="(item, index) in buildings"
            @click="selectBuilding(index)"
          >
            {{ item }}
          </div>
        </div>
      </div>

      <div class="info mb-36">
        <p class="text-normal-bold-lg mb-12 color-secondary">Info</p>
        <div class="d-flex flex-row flex-wrap">
          <div
            class="building-badge"
            v-for="(item, index) in infos"
            :class="{ 'active-badge': index === info }"
            @click="selectInfo(index)"
          >
            {{ item }}
          </div>
        </div>
      </div>

      <div class="table-responsive mb-24" v-if="showData === 'Fobs'">
        <table>
          <tr>
            <th>Envelope ID</th>
            <th>Doc name</th>
            <th>Signers</th>
            <th>Date merged</th>
            <th>Created by</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
          <tr v-for="item in 4">
            <td>089235</td>
            <td>Lanlord FObs {{ item }}</td>
            <td>Nakia Craig</td>
            <td>2023-01-19</td>
            <td>Nakia Craig</td>
            <td><div class="success-badge">Active</div></td>
            <td
              class="a-link"
              data-bs-toggle="modal"
              data-bs-target="#archiveModal"
            >
              See Enveloped Documents
            </td>
          </tr>
        </table>
      </div>
      <div class="table-responsive mb-24" v-if="showData === 'Units'">
        <table>
          <tr>
            <th>Envelope ID</th>
            <th>Doc name</th>
            <th>Signers</th>
            <th>Date merged</th>
            <th>Created by</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
          <tr v-for="item in 4">
            <td>089235</td>
            <td>Lanlord Units {{ item }}</td>
            <td>Nakia Craig</td>
            <td>2023-01-19</td>
            <td>Nakia Craig</td>
            <td><div class="success-badge">Active</div></td>
            <td
              class="a-link"
              data-bs-toggle="modal"
              data-bs-target="#archiveModal"
            >
              See Enveloped Documents
            </td>
          </tr>
        </table>
      </div>
      <div class="table-responsive mb-24" v-if="showData === 'Users'">
        <table>
          <tr>
            <th>Envelope ID</th>
            <th>Doc name</th>
            <th>Signers</th>
            <th>Date merged</th>
            <th>Created by</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
          <tr v-for="item in 4">
            <td>089235</td>
            <td>Lanlord Users {{ item }}</td>
            <td>Nakia Craig</td>
            <td>2023-01-19</td>
            <td>Nakia Craig</td>
            <td><div class="success-badge">Active</div></td>
            <td
              class="a-link"
              data-bs-toggle="modal"
              data-bs-target="#archiveModal"
            >
              See Enveloped Documents
            </td>
          </tr>
        </table>
      </div>
      <div class="table-responsive mb-24" v-if="showData === 'Wallets'">
        <table>
          <tr>
            <th>Envelope ID</th>
            <th>Doc name</th>
            <th>Signers</th>
            <th>Date merged</th>
            <th>Created by</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
          <tr v-for="item in 4">
            <td>089235</td>
            <td>Lanlord wallets {{ item }}</td>
            <td>Nakia Craig</td>
            <td>2023-01-19</td>
            <td>Nakia Craig</td>
            <td><div class="success-badge">Active</div></td>
            <td
              class="a-link"
              data-bs-toggle="modal"
              data-bs-target="#archiveModal"
            >
              See Enveloped Documents
            </td>
          </tr>
        </table>
      </div>
      <div class="table-responsive mb-24" v-if="showData === 'Appliances'">
        <table>
          <tr>
            <th>Envelope ID</th>
            <th>Doc name</th>
            <th>Signers</th>
            <th>Date merged</th>
            <th>Created by</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
          <tr v-for="item in 4">
            <td>089235</td>
            <td>Lanlord Appliances {{ item }}</td>
            <td>Nakia Craig</td>
            <td>2023-01-19</td>
            <td>Nakia Craig</td>
            <td><div class="success-badge">Active</div></td>
            <td
              class="a-link"
              data-bs-toggle="modal"
              data-bs-target="#archiveModal"
            >
              See Enveloped Documents
            </td>
          </tr>
        </table>
      </div>
      <div class="table-responsive mb-24" v-if="showData === 'Devices'">
        <table>
          <tr>
            <th>Envelope ID</th>
            <th>Doc name</th>
            <th>Signers</th>
            <th>Date merged</th>
            <th>Created by</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
          <tr v-for="item in 4">
            <td>089235</td>
            <td>Lanlord Devices {{ item }}</td>
            <td>Nakia Craig</td>
            <td>2023-01-19</td>
            <td>Nakia Craig</td>
            <td><div class="success-badge">Active</div></td>
            <td
              class="a-link"
              data-bs-toggle="modal"
              data-bs-target="#archiveModal"
            >
              See Enveloped Documents
            </td>
          </tr>
        </table>
      </div>

      <div class="d-flex justify-content-center">
        <span class="text-normal color-secondary me-12 align-self-center"
          >Showing 1 to 09 of 10 entries</span
        >
        <span class="pagination-btn">
          <img src="/images/icons/left-arrow.png" />
        </span>
        <span class="pagination-btn pagination-active">1</span>
        <span class="pagination-btn">2</span>
        <span class="pagination-btn">3</span>
        <span class="pagination-btn">
          <img src="/images/icons/right-arrow.png" />
        </span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      showData: "Fobs",
      building: 0,
      info: 0,
      infos: ["Fobs", "Units", "Users", "Wallets", "Appliances", "Devices"],
      buildings: [
        "142 N Pine",
        "143 N Pine",
        "144 N Pine",
        "146 N Pine",
        "148 N Pine",
      ],
    };
  },

  methods: {
    selectBuilding(index) {
      this.building = index;
    },
    selectInfo(index) {
      this.info = index;
      this.showData = this.infos[index];
    },
  },
};
</script>

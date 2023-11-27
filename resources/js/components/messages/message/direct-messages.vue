<template>
  <div class="direct-message">
    <div class="row mb-54">
      <div class="col-lg-6">
        <div class="d-flex flex-row flex-wrap mb-28">
          <!-- <span class="text-normal-bold-md building-badge select-cursor" v-for="(building, index) in buildings" :class="{ active: buildingIndex === index }" @click="selectBuilding(index)"> -->
          <span
            class="text-normal-bold-md building-badge select-cursor"
            :class="{ activeFolder: folder === 'inbox' }"
            @click="selectFolder('inbox')"
          >
            Inbox
          </span>
          <span
            class="text-normal-bold-md building-badge select-cursor"
            :class="{ activeFolder: folder === 'archived' }"
            @click="selectFolder('archived')"
          >
            Archived
          </span>
        </div>
      </div>
    </div>
    <div class="row" v-if="messagesList && messagesList.length > 0">
      <div class="col-lg-3 mb-12">
        <div class="card border-0 py-3 bg-light">
          <div
            class="list-item select-cursor"
            :class="'list-item-' + message.id"
            v-for="message of messagesList"
            :key="
              $gate.user.id == message.creator_id
                ? message.user_id
                : message.creator_id
            "
            @click="
              activeConversation($gate.user.id == message.creator_id ? message.user_id  : message.creator_id,message.id, true)
            "
          >
            <div class="text-end position-relative">
              <div
                class="color-secondary text-normal-bold-md position-absolute msg-time"
                :class="'time-item-' + message.id"
              >
                <!-- <div class="color-secondary text-normal-bold-md position-absolute msg-time" :class="{ 'active-time': user.id === userId }" > -->
                {{ getTimeAgo(message.created) }}
              </div>
            </div>
            <div class="d-flex justify-content-between">
              <div class="d-flex">
                <div class="avatar-sm align-self-center">
                  <img
                    src="/images/default-user.jpg"
                    class="avatar-img"
                    alt="user"
                  />
                </div>
                <div class="align-self-center text-start">
                  <!-- <div class="text-medium-bold">{{ user.name }}</div> -->
                  <div
                    class="text-medium-bold"
                    v-if="$gate.user.id == message.creator_id"
                  >
                    {{ message.user ? message.user.name : "-" }}
                  </div>
                  <div
                    class="text-medium-bold"
                    v-else-if="$gate.user.id == message.user_id"
                  >
                    {{ message.creator ? message.creator.name : "-" }}
                  </div>
                </div>
              </div>

              <div class="align-self-center">
                <!-- <div class="online-dot" v-if="user.isActive"></div> -->
                <!-- <div class="online-dot" v-if="1"></div> -->
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-9">
        <div class="mb-3">
          <button class="btn btn-custom-success" @click="openMessagePopUp">
            New message
          </button>
        </div>
        <div class="card messages-section">
          <div class="card-body messages-area" ref="messageSection">
            <template v-for="(chatInner, key) in chat">
              <div class="d-flex justify-content-center">
                <span class="date-badge">{{ formatDate(key) }}</span>
              </div>
              <template v-for="(message, index) in chatInner">
                <div
                  class="d-flex mb-2 thread"
                  v-if="message.messaged_by != $gate.user.id"
                >
                  <div class="avatar-sm mt-17">
                    <img
                      :src="'/images/default-user.jpg'"
                      alt=""
                      class="avatar-img"
                    />
                  </div>
                  <div class="align-self-center">
                    <div
                      class="bkg-primary text-white border-standered message"
                      v-if="message.body"
                    >
                      {{ message.body }}
                    </div>
                    <div class="text-center mb-20">
                      <a
                        :href="file.attachment_name"
                        v-if="
                          message.attachments && message.attachments.length > 0
                        "
                        v-for="file in message.attachments"
                        target="_blank"
                      >
                        <img
                          style="height: 100px; width: 100px"
                          :src="file.attachment_name"
                        />
                      </a>
                    </div>
                    <p class="text-end mb-0 text-normal-bold-md">
                      <!-- <a
                        :href="file.attachment_name"
                        target="_blank"
                        v-if="
                          message.attachments && message.attachments.length > 0
                        "
                        v-for="file in message.attachments"
                        ><i
                          class="fa fa-paperclip"
                          aria-hidden="true"
                          :title="file.attachment_name"
                        ></i
                      ></a>
                      | -->
                      <i
                        class="fa fa-archive select-cursor"
                        :title="folder == 'inbox' ? 'Archive' : 'Unarchive'"
                        aria-hidden="true"
                        style="color: cadetblue"
                        @click="archiveMessage(message.email_users_id)"
                      ></i>
                      |
                      {{ formatTime(message.created) }}
                    </p>
                  </div>
                </div>

                <div
                  class="d-flex flex-row-reverse border-standered thread"
                  v-else
                >
                  <div class="align-self-center">
                    <div
                      class="bkg-secondary border-standered message"
                      v-if="message.body"
                    >
                      {{ message.body }}
                    </div>
                    <div class="text-center mb-20">
                      <a
                        :href="file.attachment_name"
                        v-if="
                          message.attachments && message.attachments.length > 0
                        "
                        v-for="file in message.attachments"
                        target="_blank"
                      >
                        <div style="width: 100px">
                          <img :src="file.attachment_name" class="avatar-img" />
                        </div>
                      </a>
                    </div>
                    <p class="text-end mb-0 text-normal-bold-md">
                      <!-- <a
                        :href="file.attachment_name"
                        target="_blank"
                        v-if="
                          message.attachments && message.attachments.length > 0
                        "
                        v-for="file in message.attachments"
                        ><i
                          class="fa fa-paperclip"
                          aria-hidden="true"
                          :title="file.attachment_name"
                        ></i
                      ></a>
                      | -->
                      <i
                        class="fa fa-archive select-cursor"
                        :title="folder == 'inbox' ? 'Archive' : 'Unarchive'"
                        aria-hidden="true"
                        style="color: cadetblue"
                        @click="archiveMessage(message.email_users_id)"
                      ></i>
                      | {{ formatTime(message.created) }}
                    </p>
                  </div>
                </div>
              </template>
            </template>
          </div>

          <div class="send-message-field">
            <div class="card-body">
              <form
                @submit.prevent="sendMessage"
                class="reply-form"
                @keydown="form.onKeydown($event)"
                enctype="multipart/form-data"
              >
                <alert-error :form="form"></alert-error>
                <div class="d-flex">
                  <div class="d-none avatar-sm d-lg-block align-self-center">
                    <img
                      :src="
                        $gate.user.profile_picture &&
                        $gate.user.profile_picture != ''
                          ? $gate.user.profile_picture
                          : '/images/default-user.jpg'
                      "
                      class="avatar-img me-2"
                      alt=""
                    />
                  </div>
                  <div id="message-container">
                    <input
                      type="text"
                      id="message-input"
                      placeholder="Write a message..."
                      v-model="form.message"
                    />
                    <button class="submit-btn" type="submit">
                      <img src="/images/icons/send.png" id="send-button" />
                    </button>

                    <!-- <img src="/images/icons/emoji.png" id="emoji-button" /> -->
                    <label class="submit-btn" type="button">
                      <input
                        type="file"
                        id="replyFile"
                        name="filename"
                        class="d-none"
                        multiple
                        @change="attachAFile"
                      />
                      <img
                        src="/images/icons/attachment.png"
                        id="attachment-button"
                      />
                    </label>
                  </div>
                  <has-error :form="form" field="message"></has-error>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row" v-else>
      <div class="col-lg-12">
        <div class="mb-3" style="float: right">
          <button
            class="btn btn-custom-success"
            data-bs-toggle="modal"
            data-bs-target="#directMessageModal"
          >
            New message
          </button>
        </div>
      </div>
      <div class="col-lg-12" style="text-align: center">No message found.</div>
    </div>

    <div>
      <SendDirectMessageModal :data="users" />
    </div>
  </div>
</template>

<script>
import SendDirectMessage from "./send-direct-message.vue";
import SendDirectMessageModal from "./send-direct-message-modal.vue";

export default {
  components: {
    SendDirectMessage,
    SendDirectMessageModal,
  },

  data() {
    return {
      userId: 1,
      userConversation: {},
      messagesList: [],
      folder: "inbox",
      chat: [],
      users: [],
      form: new Form({
        users: [],
        message: "",
        attachment: [],
      }),
      activeConversationUserId: "",
      files: [],
    };
  },

  methods: {
    openMessagePopUp() {
      this.loadUsers();
      $("#directMessageModal").modal("show");
    },
    archiveMessage(id) {
      this.showLoader();
      let value = this.folder == "inbox" ? 1 : 0;
      this.$http
        .get("/message/" + id + "/archive/" + value)
        .then((response) => {
          this.fetchMessages(this.folder, this.activeConversationUserId);
          Toast.fire({
            icon: "success",
            title: "Message archived successfully.",
          });
        })
        .catch((error) => {
          console.error(error);
        });
    },
    sendMessage() {
      if (this.form.message == "" && this.form.attachment.length == 0) {
        console.log("didn;t send");
        return;
      }
      this.showLoader();
      this.form.users.push(this.activeConversationUserId);
      this.form
        .post("/api/sendMessage")
        .then(({ data }) => {
          Toast.fire({
            icon: "success",
            title: "Message sent successfully.",
          });
          this.form.message = "";
          this.form.attachment = [];
          this.fetchMessages("inbox", this.activeConversationUserId);
        })
        .catch((error) => {
          console.log(error);

          console.log("REMOVING LOADER IN sendMessage erro");
          this.removeLoader();
        });
    },
    activeConversation(id, messageId = 0, showLoader = false) {
      this.activeConversationUserId = id;
      if(showLoader) this.showLoader();
      let archived = this.folder == "inbox" ? 0 : 1;
      this.$http
        .get("/messages/user/" + id + "?archived=" + archived)
        .then((response) => {
          this.chat = response.data;
          $(".list-item").removeClass("active");
          $(".list-item-" + messageId).addClass("active");

          $(".msg-time").removeClass("active-time");
          $(".time-item-" + messageId).addClass("active-time");

          console.log("REMOVING LOADER IN activeConversation");
          this.removeLoader();
          // for scroll down to start
          const container = this.$refs.messageSection;
          setTimeout(() => {
            container.scrollTop = container.scrollHeight;
          }, 500);
        })
        .catch((error) => {
          console.error(error);
        });
    },
    fetchMessages(type, activeConversationId = 0) {
      this.showLoader();
      this.$http
        .get("/messages/" + type)
        .then((response) => {
          this.messagesList = response.data.data;
          console.log(this.messagesList, "MESSA");
          if (this.messagesList && this.messagesList.length > 0) {
            let id = this.messagesList[0].creator_id;
            let messageId = this.messagesList[0].id;
            if (
              this.$gate.user.id == this.messagesList[0].creator_id &&
              activeConversationId == 0
            ) {
              id = this.messagesList[0].user_id;
            }
            if (activeConversationId != 0) id = this.activeConversationUserId;

            this.activeConversation(id, messageId);
          } else {
            console.log("REMOVING LOADER");
            this.removeLoader();
          }
          // resolve();
        })
        .catch((error) => {
          console.error(error);
        });
    },
    bkpfetchMessages(type, activeConversationId = 0) {
      this.showLoader();

      return new Promise((resolve, reject) => {
        fetch("api/messages/" + type)
          .then((response) => response.json())
          .then((data) => {
            this.messagesList = data.data;
            console.log(this.messagesList, "MESSA");
            if (this.messagesList && this.messagesList.length > 0) {
              let id = this.messagesList[0].creator_id;
              let messageId = this.messagesList[0].id;
              if (
                this.$gate.user.id == this.messagesList[0].creator_id &&
                activeConversationId == 0
              ) {
                id = this.messagesList[0].user_id;
              }
              if (activeConversationId != 0) id = this.activeConversationUserId;

              this.activeConversation(id, messageId);
            } else {
              console.log("REMOVING LOADER IN bkpfetchMessages else");
              this.removeLoader();
            }
            resolve();
          })
          .catch((error) => {
            console.error(error);
            reject(error);
          });
      });
    },
    loadUsers() {
      this.$http
          .get("/getUsers")
        .then((response) => {
          this.users = response.data.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    selectFolder(type) {
      this.folder = type;
      this.fetchMessages(type);
    },
    attachAFile() {
      var files = $("#replyFile")[0].files;
      for (var i = 0; i < files.length; i++) {
        var fileup = files[i];
        this.form.attachment.push(fileup);
        this.files.push(fileup.name);
        console.log(this.form.attachment);
        // Toast.fire({
        //   icon: "success",
        //   title: this.files.join(" , ") + " files ready to send",
        // });
        this.sendMessage();
        // formData.append($(".EmailAttachmentName").attr("name"), fileup, fileup.name);
      }
    },
  },
  created() {
    this.fetchMessages("inbox");
  },
  mounted() {
    console.log("PAGE RENDERD", this.messagesList);
  },
};
</script>

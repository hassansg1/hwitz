<template>
  <div class="digital-section">
    <div
      class="modal fade"
      id="addNewDisplayModal"
      tabindex="-1"
      aria-labelledby="addNewDisplayModalLabel"
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
            <h3 class="h3 mb-12">Add New Display</h3>
            <p class="text-medium color-secondary">
              Lorem, ipsum dolor sit amet consectetur adipisicing elit. Sequi
              necessitatibus
            </p>

            <div class="">
              <div class="row mb-40">
                <div class="col-lg-6">
                  <label class="text-normal-bold-md mb-12 color-secondary">
                    Title
                  </label>
                  <input type="text" class="form-control" />
                </div>
              </div>
              <div class="row mb-40">
                <div class="col-lg-8">
                  <label class="text-normal-bold-md mb-12 color-secondary">
                    Select content type
                  </label>
                  <div class="d-flex flex-row flex-wrap">
                    <div class="mb-20 me-20">
                      <div
                        class="selectContent mb-6"
                        @click="selectContentType('image')"
                        :class="{
                          outlineActive: selectedContentType === 'image',
                        }"
                      >
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="30"
                          height="26"
                          viewBox="0 0 30 26"
                          fill="none"
                        >
                          <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M12.7788 0.000196581C12.8188 0.000293437 12.8586 0.000389809 12.8981 0.000389809H17.1019C17.1414 0.000389809 17.1812 0.000293437 17.2212 0.000196581C17.6952 -0.000951358 18.2012 -0.00217659 18.671 0.155812C19.0797 0.293255 19.4519 0.517562 19.7608 0.812501C20.1158 1.15153 20.341 1.59331 20.5519 2.00719C20.5697 2.04212 20.5874 2.07686 20.6051 2.11131L21.1892 3.25034L23.4674 3.25034C24.3576 3.25032 25.1007 3.25031 25.7078 3.29867C26.3409 3.3491 26.9353 3.45816 27.4975 3.7374C28.3599 4.16583 29.061 4.84945 29.5004 5.69028C29.7869 6.23833 29.8987 6.81789 29.9504 7.43519C30 8.02705 30 8.75154 30 9.61951V19.6308C30 20.4988 30 21.2233 29.9504 21.8152C29.8987 22.4325 29.7869 23.012 29.5004 23.5601C29.061 24.4009 28.3599 25.0845 27.4975 25.5129C26.9353 25.7922 26.3409 25.9012 25.7078 25.9517C25.1007 26 24.3577 26 23.4674 26H6.53258C5.64234 26 4.89927 26 4.29222 25.9517C3.65908 25.9012 3.06465 25.7922 2.50254 25.5129C1.64014 25.0845 0.938974 24.4009 0.499555 23.5601C0.213148 23.012 0.101296 22.4325 0.0495666 21.8152C-3.1814e-05 21.2233 -1.7112e-05 20.4988 6.70014e-07 19.6308V9.61954C-1.7112e-05 8.75155 -3.1814e-05 8.02706 0.0495666 7.43519C0.101296 6.81789 0.213148 6.23833 0.499555 5.69028C0.938974 4.84945 1.64014 4.16583 2.50254 3.7374C3.06465 3.45816 3.65908 3.3491 4.29222 3.29867C4.89927 3.25031 5.64235 3.25032 6.53261 3.25034L8.81079 3.25034L9.39492 2.11131C9.41259 2.07685 9.43029 2.04212 9.4481 2.00718C9.65903 1.59331 9.88418 1.15153 10.2392 0.812501C10.5481 0.517562 10.9203 0.293255 11.329 0.155812C11.7988 -0.00217659 12.3048 -0.000951358 12.7788 0.000196581ZM12.1406 2.46109C12.1406 2.46108 12.1413 2.46084 12.1431 2.46042L12.1406 2.46109ZM12.2698 2.44659C12.1868 2.45164 12.1522 2.45827 12.1431 2.46042C12.0854 2.47997 12.033 2.51161 11.9893 2.55316C11.9832 2.56013 11.9617 2.5873 11.92 2.65742C11.8522 2.77127 11.7715 2.92732 11.631 3.20138L10.7014 5.01411C10.4896 5.42699 10.0568 5.6878 9.58333 5.6878H6.58333C5.62929 5.6878 4.98893 5.68875 4.4958 5.72803C4.01726 5.76615 3.78846 5.83421 3.63752 5.90919C3.24552 6.10393 2.92681 6.41467 2.72707 6.79687C2.65017 6.94403 2.58036 7.16711 2.54126 7.63368C2.50097 8.11447 2.5 8.73881 2.5 9.66899V19.5813C2.5 20.5115 2.50097 21.1359 2.54126 21.6167C2.58036 22.0832 2.65017 22.3063 2.72707 22.4535C2.92681 22.8357 3.24552 23.1464 3.63752 23.3411C3.78846 23.4161 4.01726 23.4842 4.4958 23.5223C4.98893 23.5616 5.62929 23.5625 6.58333 23.5625H23.4167C24.3707 23.5625 25.0111 23.5616 25.5042 23.5223C25.9827 23.4842 26.2115 23.4161 26.3625 23.3411C26.7545 23.1464 27.0732 22.8357 27.2729 22.4535C27.3498 22.3063 27.4196 22.0832 27.4587 21.6167C27.499 21.1359 27.5 20.5115 27.5 19.5813V9.66899C27.5 8.73881 27.499 8.11447 27.4587 7.63368C27.4196 7.16711 27.3498 6.94403 27.2729 6.79687C27.0732 6.41467 26.7545 6.10393 26.3625 5.90919C26.2115 5.83421 25.9827 5.76615 25.5042 5.72803C25.0111 5.68875 24.3707 5.6878 23.4167 5.6878H20.4167C19.9432 5.6878 19.5104 5.42699 19.2986 5.01411L18.369 3.20138C18.2285 2.92732 18.1478 2.77127 18.08 2.65742C18.0383 2.58729 18.0168 2.56012 18.0107 2.55316C17.967 2.51161 17.9145 2.47994 17.8568 2.46039C17.8584 2.4609 17.8593 2.46111 17.8594 2.46109M17.8568 2.46039C17.8477 2.45825 17.8132 2.45164 17.7302 2.44659C17.5954 2.4384 17.4162 2.43785 17.1019 2.43785H12.8981C12.5838 2.43785 12.4046 2.4384 12.2698 2.44659M18.0089 2.55122C18.0089 2.55117 18.0096 2.55181 18.0107 2.55316L18.0089 2.55122ZM11.9911 2.55122C11.991 2.55128 11.9904 2.55206 11.9893 2.55316L11.9911 2.55122ZM15 10.5627C12.6988 10.5627 10.8333 12.3815 10.8333 14.6252C10.8333 16.8688 12.6988 18.6876 15 18.6876C17.3012 18.6876 19.1667 16.8688 19.1667 14.6252C19.1667 12.3815 17.3012 10.5627 15 10.5627ZM8.33333 14.6252C8.33333 11.0354 11.3181 8.12527 15 8.12527C18.6819 8.12527 21.6667 11.0354 21.6667 14.6252C21.6667 18.215 18.6819 21.1251 15 21.1251C11.3181 21.1251 8.33333 18.215 8.33333 14.6252Z"
                            fill="black"
                            fill-opacity="0.4"
                          />
                        </svg>
                      </div>
                      <p class="text-center text-medium">Image</p>
                    </div>
                    <div class="mb-20 me-20">
                      <div
                        class="selectContent mb-6"
                        @click="selectContentType('video')"
                        :class="{
                          outlineActive: selectedContentType === 'video',
                        }"
                      >
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="30"
                          height="23"
                          viewBox="0 0 30 23"
                          fill="none"
                        >
                          <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M6.53261 6.60442e-07H15.1341C16.0243 -1.68676e-05 16.7674 -3.13595e-05 17.3745 0.0488585C18.0076 0.0998492 18.602 0.210103 19.1641 0.492418C20.0265 0.92556 20.7277 1.6167 21.1671 2.46679C21.4535 3.02087 21.5654 3.6068 21.6171 4.2309C21.6506 4.63458 21.6614 5.09928 21.665 5.62837L24.1171 4.17812C24.7362 3.81192 25.2734 3.4942 25.724 3.28024C26.1791 3.06409 26.7348 2.85712 27.354 2.91401C28.1839 2.99026 28.941 3.41281 29.4343 4.075C29.8024 4.56911 29.9082 5.14529 29.9544 5.64123C30.0001 6.13219 30 6.74971 30 7.46148V15.5385C30 16.2503 30.0001 16.8678 29.9544 17.3588C29.9082 17.8547 29.8024 18.4309 29.4343 18.925C28.941 19.5872 28.1839 20.0097 27.354 20.086C26.7348 20.1429 26.1791 19.9359 25.724 19.7198C25.2734 19.5058 24.7362 19.1881 24.117 18.8219L21.665 17.3716C21.6614 17.9007 21.6506 18.3654 21.6171 18.7691C21.5654 19.3932 21.4535 19.9791 21.1671 20.5332C20.7277 21.3833 20.0265 22.0744 19.1641 22.5076C18.602 22.7899 18.0076 22.9002 17.3745 22.9511C16.7674 23 16.0243 23 15.1341 23H6.53258C5.64234 23 4.89927 23 4.29222 22.9511C3.65908 22.9002 3.06465 22.7899 2.50254 22.5076C1.64014 22.0744 0.938974 21.3833 0.499555 20.5332C0.213148 19.9791 0.101296 19.3932 0.0495666 18.7691C-3.1814e-05 18.1707 -1.7112e-05 17.4383 6.70014e-07 16.5607V6.43929C-1.7112e-05 5.56175 -3.1814e-05 4.82928 0.0495666 4.2309C0.101296 3.6068 0.213148 3.02087 0.499555 2.46679C0.938974 1.6167 1.64014 0.92556 2.50254 0.492418C3.06465 0.210103 3.65908 0.0998492 4.29222 0.0488585C4.89927 -3.13595e-05 5.64235 -1.68676e-05 6.53261 6.60442e-07ZM21.6667 14.4988L25.3545 16.6799C26.0366 17.0833 26.4745 17.3408 26.809 17.4997C27.0175 17.5987 27.1162 17.6237 27.1443 17.6297C27.2466 17.6148 27.3398 17.5628 27.4054 17.484C27.4146 17.4571 27.4437 17.3609 27.4648 17.1336C27.4987 16.7692 27.5 16.2666 27.5 15.4824V7.51756C27.5 6.73343 27.4987 6.23084 27.4648 5.86638C27.4437 5.63915 27.4146 5.54289 27.4054 5.51605C27.3398 5.43719 27.2466 5.38521 27.1443 5.3703C27.1162 5.37632 27.0175 5.40131 26.809 5.50034C26.4745 5.65918 26.0366 5.9167 25.3545 6.32013L21.6667 8.5012V14.4988ZM4.4958 2.50496C4.01726 2.5435 3.78846 2.61231 3.63752 2.68811C3.24552 2.885 2.92681 3.19915 2.72707 3.58556C2.65017 3.73433 2.58036 3.95987 2.54126 4.43157C2.50097 4.91766 2.5 5.54887 2.5 6.48929V16.5107C2.5 17.4511 2.50097 18.0823 2.54126 18.5684C2.58036 19.0401 2.65017 19.2657 2.72707 19.4144C2.92681 19.8008 3.24552 20.115 3.63752 20.3119C3.78846 20.3877 4.01726 20.4565 4.4958 20.495C4.98893 20.5348 5.62929 20.5357 6.58333 20.5357H15.0833C16.0374 20.5357 16.6777 20.5348 17.1709 20.495C17.6494 20.4565 17.8782 20.3877 18.0291 20.3119C18.4212 20.115 18.7399 19.8008 18.9396 19.4144C19.0165 19.2657 19.0863 19.0401 19.1254 18.5684C19.1657 18.0823 19.1667 17.4511 19.1667 16.5107V6.48929C19.1667 5.54887 19.1657 4.91766 19.1254 4.43157C19.0863 3.95987 19.0165 3.73433 18.9396 3.58556C18.7399 3.19915 18.4212 2.885 18.0291 2.68811C17.8782 2.61231 17.6494 2.5435 17.1709 2.50496C16.6777 2.46525 16.0374 2.46429 15.0833 2.46429H6.58333C5.62929 2.46429 4.98893 2.46525 4.4958 2.50496Z"
                            fill="black"
                            fill-opacity="0.4"
                          />
                        </svg>
                      </div>
                      <p class="text-center text-medium">Video</p>
                    </div>
                    <div class="mb-20 me-20">
                      <div
                        class="selectContent mb-6"
                        @click="selectContentType('building')"
                        :class="{
                          outlineActive: selectedContentType === 'building',
                        }"
                      >
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="31"
                          height="31"
                          viewBox="0 0 31 31"
                          fill="none"
                        >
                          <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M7.44651 2.1425e-06H16.6646C17.108 -2.96798e-05 17.5153 -5.88327e-05 17.8558 0.0277578C18.2228 0.0577479 18.6244 0.1264 19.0211 0.328498C19.5882 0.617451 20.0492 1.07852 20.3382 1.64562C20.5403 2.04226 20.6089 2.44383 20.6389 2.81089C20.6667 3.15134 20.6667 3.55861 20.6667 4.00205L20.6667 10.3333L23.5535 10.3333C23.9969 10.3333 24.4042 10.3333 24.7447 10.3611C25.1117 10.3911 25.5133 10.4597 25.9099 10.6618C26.477 10.9508 26.9381 11.4118 27.2271 11.9789C27.4292 12.3756 27.4978 12.7772 27.5278 13.1442C27.5556 13.4847 27.5556 13.8919 27.5556 14.3354L27.5556 28.4167H29.7083C30.4217 28.4167 31 28.995 31 29.7083C31 30.4217 30.4217 31 29.7083 31H1.29167C0.578299 31 0 30.4217 0 29.7083C0 28.995 0.578299 28.4167 1.29167 28.4167H3.44444V4.04723C3.44444 4.03214 3.44444 4.01708 3.44444 4.00207C3.44441 3.55862 3.44438 3.15135 3.4722 2.81089C3.50219 2.44383 3.57084 2.04226 3.77294 1.64562C4.06189 1.07852 4.52296 0.61745 5.09006 0.328498C5.4867 0.126401 5.88827 0.0577479 6.25533 0.0277578C6.59579 -5.88327e-05 7.00307 -2.96798e-05 7.44651 2.1425e-06ZM6.02778 28.4167H18.0833V4.04723C18.0833 3.54365 18.0823 3.24364 18.0642 3.02126C18.0531 2.88553 18.0384 2.82848 18.0337 2.81319C17.9932 2.73631 17.9304 2.67348 17.8535 2.63298C17.8382 2.62823 17.7811 2.6136 17.6454 2.60251C17.423 2.58434 17.123 2.58334 16.6194 2.58334H7.49167C6.98809 2.58334 6.68808 2.58434 6.4657 2.60251C6.32997 2.6136 6.27292 2.62823 6.25763 2.63298C6.18075 2.67348 6.11793 2.73631 6.07742 2.81319C6.07267 2.82848 6.05804 2.88553 6.04695 3.02126C6.02878 3.24364 6.02778 3.54365 6.02778 4.04723V28.4167ZM6.07897 2.80873C6.07899 2.80878 6.07869 2.8097 6.07801 2.81134L6.07897 2.80873ZM6.25317 2.63453C6.25312 2.63452 6.25394 2.63418 6.25578 2.63357L6.25317 2.63453ZM17.8579 2.63453C17.8579 2.63455 17.857 2.63425 17.8554 2.63358L17.8579 2.63453ZM18.0321 2.80873C18.0322 2.80868 18.0325 2.80949 18.0331 2.81129L18.0321 2.80873ZM20.6667 12.9167V28.4167H24.9722V14.3806C24.9722 13.877 24.9712 13.577 24.953 13.3546C24.942 13.2189 24.9273 13.1618 24.9226 13.1465C24.8821 13.0696 24.8192 13.0068 24.7424 12.9663C24.7271 12.9616 24.67 12.9469 24.5343 12.9358C24.3119 12.9177 24.0119 12.9167 23.5083 12.9167H20.6667ZM24.7468 12.9679C24.7468 12.9679 24.7459 12.9676 24.7442 12.9669L24.7468 12.9679ZM24.921 13.1421C24.921 13.142 24.9214 13.1428 24.922 13.1447L24.921 13.1421ZM8.61111 8.18056C8.61111 7.46719 9.18941 6.88889 9.90278 6.88889H14.2083C14.9217 6.88889 15.5 7.46719 15.5 8.18056C15.5 8.89393 14.9217 9.47223 14.2083 9.47223H9.90278C9.18941 9.47223 8.61111 8.89393 8.61111 8.18056ZM8.61111 15.5C8.61111 14.7866 9.18941 14.2083 9.90278 14.2083H14.2083C14.9217 14.2083 15.5 14.7866 15.5 15.5C15.5 16.2134 14.9217 16.7917 14.2083 16.7917H9.90278C9.18941 16.7917 8.61111 16.2134 8.61111 15.5ZM8.61111 22.8194C8.61111 22.1061 9.18941 21.5278 9.90278 21.5278H14.2083C14.9217 21.5278 15.5 22.1061 15.5 22.8194C15.5 23.5328 14.9217 24.1111 14.2083 24.1111H9.90278C9.18941 24.1111 8.61111 23.5328 8.61111 22.8194Z"
                            fill="black"
                            fill-opacity="0.4"
                          />
                        </svg>
                      </div>
                      <p class="text-center text-medium">Building directory</p>
                    </div>
                    <div class="mb-20 me-20">
                      <div
                        class="selectContent mb-6"
                        @click="selectContentType('website')"
                        :class="{
                          outlineActive: selectedContentType === 'website',
                        }"
                      >
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          width="29"
                          height="29"
                          viewBox="0 0 29 29"
                          fill="none"
                        >
                          <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M6.31486 6.4768e-07H22.6851C23.5457 -1.65416e-05 24.264 -3.07535e-05 24.8509 0.0479144C25.4629 0.0979197 26.0375 0.206043 26.5809 0.482903C27.4145 0.907675 28.0923 1.58546 28.5171 2.41913C28.794 2.96249 28.9021 3.53711 28.9521 4.14914C29 4.73596 29 5.45426 29 6.31483V16.2407C29 17.1013 29 17.8196 28.9521 18.4064C28.9021 19.0185 28.794 19.5931 28.5171 20.1364C28.0923 20.9701 27.4145 21.6479 26.5809 22.0727C26.0375 22.3495 25.4629 22.4576 24.8509 22.5076C24.264 22.5556 23.5457 22.5556 22.6852 22.5556H15.7083V26.5833H19.7361C20.4035 26.5833 20.9444 27.1243 20.9444 27.7917C20.9444 28.459 20.4035 29 19.7361 29H9.26389C8.59655 29 8.05556 28.459 8.05556 27.7917C8.05556 27.1243 8.59655 26.5833 9.26389 26.5833H13.2917V22.5556H6.31483C5.45426 22.5556 4.73596 22.5556 4.14914 22.5076C3.53711 22.4576 2.96249 22.3495 2.41913 22.0727C1.58546 21.6479 0.907675 20.9701 0.482903 20.1364C0.206043 19.5931 0.0979197 19.0185 0.0479144 18.4064C-3.07535e-05 17.8196 -1.65416e-05 17.1013 6.4768e-07 16.2407V6.31486C-1.65416e-05 5.45428 -3.07535e-05 4.73596 0.0479144 4.14914C0.0979197 3.53711 0.206043 2.96249 0.482903 2.41913C0.907675 1.58546 1.58546 0.907675 2.41913 0.482903C2.96249 0.206043 3.53711 0.0979197 4.14914 0.0479144C4.73596 -3.07535e-05 5.45427 -1.65416e-05 6.31486 6.4768e-07ZM2.41779 16.9167C2.42036 17.4586 2.42882 17.8702 2.45655 18.2096C2.49435 18.6722 2.56183 18.8934 2.63617 19.0393C2.82925 19.4182 3.13733 19.7263 3.51627 19.9194C3.66217 19.9937 3.88335 20.0612 4.34594 20.099C4.82263 20.138 5.44164 20.1389 6.36389 20.1389H22.6361C23.5584 20.1389 24.1774 20.138 24.6541 20.099C25.1166 20.0612 25.3378 19.9937 25.4837 19.9194C25.8627 19.7263 26.1708 19.4182 26.3638 19.0393C26.4382 18.8934 26.5056 18.6722 26.5434 18.2096C26.5712 17.8702 26.5796 17.4586 26.5822 16.9167H2.41779ZM26.5833 14.5H2.41667V6.36389C2.41667 5.44164 2.41761 4.82263 2.45655 4.34594C2.49435 3.88335 2.56183 3.66217 2.63617 3.51627C2.82925 3.13733 3.13733 2.82925 3.51627 2.63617C3.66217 2.56183 3.88335 2.49435 4.34594 2.45656C4.82263 2.41761 5.44164 2.41667 6.36389 2.41667H22.6361C23.5584 2.41667 24.1774 2.41761 24.6541 2.45656C25.1166 2.49435 25.3378 2.56183 25.4837 2.63617C25.8627 2.82925 26.1708 3.13733 26.3638 3.51627C26.4382 3.66217 26.5056 3.88335 26.5434 4.34594C26.5824 4.82263 26.5833 5.44164 26.5833 6.36389V14.5Z"
                            fill="black"
                            fill-opacity="0.4"
                          />
                        </svg>
                      </div>
                      <p class="text-center text-medium">Website</p>
                    </div>
                  </div>
                </div>
                <div
                  class="multiselect-border col-lg-4"
                  v-if="selectedContentType == 'image'"
                >
                  <div class="dropdown-wrapper">
                    <label class="dropdown-label text-capitalize"
                      >Image gallery</label
                    >
                    <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                      v-model="imageValue"
                      tag-placeholder="Add this as new tag"
                      placeholder=""
                      label="name"
                      track-by="code"
                      :options="imageOptions"
                      :multiple="false"
                    ></multiselect>
                  </div>
                </div>
                <div
                  class="multiselect-border col-lg-4"
                  v-if="selectedContentType == 'video'"
                >
                  <div class="dropdown-wrapper">
                    <label class="dropdown-label text-capitalize"
                      >Video gallery</label
                    >
                    <multiselect 
                  :selectLabel="''"
                  :deselectLabel= "''"
                      v-model="videoValue"
                      tag-placeholder="Add this as new tag"
                      placeholder=""
                      label="name"
                      track-by="code"
                      :options="videoOptions"
                      :multiple="false"
                    ></multiselect>
                  </div>
                </div>
              </div>

              <div class="row mb-40" v-if="selectedContentType == 'building'">
                <div class="col-lg-8">
                  <p class="text-normal-bold-md mb-12 color-secondary">
                    Recipients
                  </p>
                  <div class="d-flex">
                    <div>
                      <div class="badge-gray filledActive me-40">
                        All recipients
                      </div>
                    </div>

                    <div class="recipients-list">
                      <div class="d-flex mb-20">
                        <div class="align-self-center">
                          <input
                            class="form-check-input mt-0 me-12"
                            type="checkbox"
                            value=""
                            id="flexCheckDefault"
                          />
                        </div>

                        <div class="d-flex">
                          <div class="avatar-sm me-8">
                            <img
                              src="/images/test-avatar.png"
                              class="avatar-img"
                              alt=""
                            />
                          </div>
                          <div>
                            <p class="text-medium-bold mb-4">Emma Jones</p>
                            <p class="text-normal color-primary mb-0">
                              777 - 222 - 1234
                            </p>
                          </div>
                        </div>
                      </div>
                      <div class="d-flex mb-20">
                        <div class="align-self-center">
                          <input
                            class="form-check-input mt-0 me-12"
                            type="checkbox"
                            value=""
                            id="flexCheckDefault"
                          />
                        </div>

                        <div class="d-flex">
                          <div class="avatar-sm me-8">
                            <img
                              src="/images/test-avatar.png"
                              class="avatar-img"
                              alt=""
                            />
                          </div>
                          <div>
                            <p class="text-medium-bold mb-4">Emma Jones</p>
                            <p class="text-normal color-primary mb-0">
                              777 - 222 - 1234
                            </p>
                          </div>
                        </div>
                      </div>
                      <div class="d-flex mb-20">
                        <div class="align-self-center">
                          <input
                            class="form-check-input mt-0 me-12"
                            type="checkbox"
                            value=""
                            id="flexCheckDefault"
                          />
                        </div>

                        <div class="d-flex">
                          <div class="avatar-sm me-8">
                            <img
                              src="/images/test-avatar.png"
                              class="avatar-img"
                              alt=""
                            />
                          </div>
                          <div>
                            <p class="text-medium-bold mb-4">Emma Jones</p>
                            <p class="text-normal color-primary mb-0">
                              777 - 222 - 1234
                            </p>
                          </div>
                        </div>
                      </div>
                      <div class="d-flex mb-20">
                        <div class="align-self-center">
                          <input
                            class="form-check-input mt-0 me-12"
                            type="checkbox"
                            value=""
                            id="flexCheckDefault"
                          />
                        </div>

                        <div class="d-flex">
                          <div class="avatar-sm me-8">
                            <img
                              src="/images/test-avatar.png"
                              class="avatar-img"
                              alt=""
                            />
                          </div>
                          <div>
                            <p class="text-medium-bold mb-4">Emma Jones</p>
                            <p class="text-normal color-primary mb-0">
                              777 - 222 - 1234
                            </p>
                          </div>
                        </div>
                      </div>
                      <div class="d-flex mb-20">
                        <div class="align-self-center">
                          <input
                            class="form-check-input mt-0 me-12"
                            type="checkbox"
                            value=""
                            id="flexCheckDefault"
                          />
                        </div>

                        <div class="d-flex">
                          <div class="avatar-sm me-8">
                            <img
                              src="/images/test-avatar.png"
                              class="avatar-img"
                              alt=""
                            />
                          </div>
                          <div>
                            <p class="text-medium-bold mb-4">Emma Jones</p>
                            <p class="text-normal color-primary mb-0">
                              777 - 222 - 1234
                            </p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row mb-40" v-if="selectedContentType == 'website'">
                <div class="col-lg-6">
                  <p class="text-normal-bold-md mb-12 color-secondary">
                    Enter link
                  </p>

                  <input type="text" class="form-control" />
                </div>
              </div>

              <div class="row mb-40">
                <div class="col-lg-8">
                  <p class="text-normal-bold-md mb-12 color-secondary">
                    How frequently
                  </p>

                  <div class="d-flex flex-row flex-wrap">
                    <div
                      class="badge-gray me-12"
                      v-for="item in frequently"
                      :class="{ filledActive: item == frequentlyItemSelected }"
                      @click="selectFrequently(item)"
                    >
                      {{ item }}
                    </div>
                  </div>
                </div>
              </div>

              <div
                class="row mb-40"
                v-if="
                  selectedContentType == 'image' ||
                  selectedContentType == 'video'
                "
              >
                <div class="col-lg-8">
                  <p class="text-normal-bold-md mb-12 color-secondary">
                    Select location
                  </p>

                  <div class="d-flex flex-row flex-wrap">
                    <div
                      class="badge-gray me-12"
                      v-for="item in location"
                      :class="{ filledActive: item == locationItemSelected }"
                      @click="selectLocation(item)"
                    >
                      {{ item }}
                    </div>
                  </div>
                </div>
              </div>

              <div
                class="row"
                v-if="
                  selectedContentType == 'image' ||
                  selectedContentType == 'video'
                "
              >
                <div class="col-lg-6">
                  <p class="text-normal-bold-md mb-12 color-secondary">
                    Upload content from library image on the right bar
                  </p>

                  <label class="custom-file-upload-style mb-6">
                    <input
                      type="file"
                      id="broadcastAttachment"
                      class="d-none"
                    />
                    <div class="d-flex justify-content-end">
                      <div class="align-self-center">+</div>
                    </div>
                  </label>
                </div>
              </div>

              <div id="scheduleDisplay" class="mb-20 overflow-hidden"></div>
              <div class="d-flex justify-content-end">
                <button class="btn btn-dark me-20">Cancel</button>
                <button class="btn btn-primary">Save Advertisement</button>
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
  data() {
    return {
      selectedContentType: "",
      frequentlyItemSelected: "",
      frequently: ["Low", "Medium", "high", "Always"],

      locationItemSelected: "",
      location: ["Reception", "Hallway", "Gym", "Laundry"],
      imageValue: [{ name: "Image 1", code: "1" }],
      imageOptions: [
        { name: "Image 1", code: "1" },
        { name: "Image 2", code: "2" },
        { name: "Image 3", code: "3" },
        { name: "Image 4", code: "4" },
      ],
      videoValue: [{ name: "Video 1", code: "1" }],
      videoOptions: [
        { name: "Video 2", code: "1" },
        { name: "Video 2", code: "2" },
        { name: "Video 3", code: "3" },
        { name: "Video 4", code: "4" },
      ],
    };
  },

  methods: {
    selectFrequently(item) {
      this.frequentlyItemSelected = item;
    },

    selectLocation(item) {
      this.locationItemSelected = item;
    },

    selectContentType(item) {
      this.selectedContentType = item;
    },
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
      $("#scheduleDisplay").jqs({
        mode: "edit",
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
};
</script>

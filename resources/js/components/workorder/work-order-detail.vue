<template>
  <div class="work-order-detail">
    <div class="row">
      <div class="col-lg-8">
        <div class="d-flex mb-37">
          <div>
            <h3 class="h3 me-16">Windows cleaning</h3>
          </div>
          <div>
            <img src="/images/icons/edit.png" alt="" />
          </div>
        </div>

        <div class="mb-34">
          <p class="text-normal-bold-md mb-12 color-secondary">Instructions</p>
          <textarea name="" id="" rows="5"></textarea>
        </div>

        <div class="mb-34">
          <p class="text-normal-bold-md mb-12 color-secondary">Attachment</p>
          <div class="d-flex gap-3">
            <div class="d-flex align-items-center text-center btn-attachment">
              <div class="mx-auto text-center text-white btn-attachment-icon">
                +
              </div>
            </div>

            <div>
              <img src="/images/Rectangle.png" class="img-fluid" alt="" />
            </div>
            <div>
              <img src="/images/Rectangle.png" class="img-fluid" alt="" />
            </div>
            <div>
              <img src="/images/Rectangle.png" class="img-fluid" alt="" />
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="mb-34">
          <p class="text-normal-bold-md mb-12 color-secondary">Created By</p>
          <div class="img-name border">
            <div class="d-flex">
              <div class="avatar-sm">
                <img src="/images/worker.png" class="avatar-img" alt="" />
              </div>
              <div>John Doe</div>
            </div>
          </div>
        </div>

        <div class="mb-34">
          <div class="row g-5">
            <div class="col-lg-6">
              <p class="text-normal-bold-md mb-12 color-secondary">
                Date Created
              </p>
              <div class="date-sec">12/03/2022</div>
            </div>
            <div class="col-lg-6">
              <p class="text-normal-bold-md mb-12 color-secondary">Due Date</p>
              <div class="date-sec">12/05/2022</div>
            </div>
          </div>
        </div>

        <div class="mb-34">
          <p class="text-normal-bold-md mb-12 color-secondary">Assignee</p>
          <div class="img-name border">
            <div class="d-flex flex-wrap">
              <div class="d-flex me-24">
                <div class="avatar-sm">
                  <img src="/images/worker.png" class="avatar-img" alt="" />
                </div>
                <div>John Doe</div>
              </div>
              <div class="d-flex me-24">
                <div class="avatar-sm">
                  <img src="/images/worker.png" class="avatar-img" alt="" />
                </div>
                <div>John Doe</div>
              </div>
            </div>
          </div>
        </div>

        <div class="mb-34">
          <div class="row g-5">
            <div class="col-lg-6">
              <p class="text-normal-bold-md mb-12 color-secondary">Building</p>
              <div class="date-sec">146 D Addison</div>
            </div>
            <div class="col-lg-6">
              <p class="text-normal-bold-md mb-12 color-secondary">unit</p>
              <div class="date-sec">D block</div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row gx-5">
      <div class="col-lg-8">
        <div class="d-flex gap-3">
          <div>
            <img src="/images/worker.png" class="avator-sm" alt="avatar" />
          </div>
          <div class="comments p-2 w-100">
            <textarea class="border-0" rows="5"></textarea>
            <div>
              <img src="/images/attachment.png" alt="" />
              <img src="/images/emoji.png" alt="" />
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
</template>

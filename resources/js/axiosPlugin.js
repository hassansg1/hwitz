import axios from './axios';
import { config } from './config';


import router from './router';

axios.interceptors.response.use(
  response => {
    // Do something with successful responses
    return response;
  },
  error => {
    if (error.response && error.response.status === 401) {
      // Redirect to login page
      $("div.dataprocessing").remove();
      Swal.fire({
          title: config.confirmBoxTitle,
          text: "Your session has expired. Would you like to be redirected to the login page?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: config.confirmButtonColor,
          cancelButtonColor: config.cancelButtonColor,
          confirmButtonText: config.confirmButtonText,
          closeOnConfirm: false
      }).then((result) => {
          if (result.value) {
            window.location = '/login';
          }
      });
    }
    return Promise.reject(error);
  }
);


const axiosPlugin = {
  install(Vue) {
    Vue.prototype.$http = axios;
  },
};

export default axiosPlugin;

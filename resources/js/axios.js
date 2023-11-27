import axios from 'axios';

const axiosInstance = axios.create({
  // Set your base URL or any other configuration options here
  baseURL: '/api', // Assuming your Laravel API routes start with /api
});

export default axiosInstance;

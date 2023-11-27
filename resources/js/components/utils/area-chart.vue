<template>
  <LineChartGenerator :data="lineChartData" :options="chartOptions" />
</template>

<script>
import { Line as LineChartGenerator } from "vue-chartjs";
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  Filler,
} from "chart.js";

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Filler,
  Title,
  Tooltip,
  Legend
);
export default {
  name: "TimeChartForUser",
  components: {
    LineChartGenerator,
  },
  data() {
    return {
      lineChartData: {
        labels: ["Sun", "Mon", "Tue", "Wed", "Thur", "Fri", "Sat"],
        datasets: [
          {
            label: "Usage",
            data: [0.1, 0.2, 0.1, 0.3, 0.5, 0.4, 0.3],
            borderColor: "#47C5FE",
            backgroundColor: (ctx) => {
              const canvas = ctx.chart.ctx;
              const gradient = canvas.createLinearGradient(0, 0, 0, 160);
              gradient.addColorStop(0, "#47C5FE");
              gradient.addColorStop(0.9961, "rgba(0, 194, 255, 0.1001)");
              return gradient;
            },
            tension: 0.5,
            fill: true,
            pointStyle: false,
          },
        ],
      },
      chartOptions: {
        responsive: true,
        plugins: {
          legend: {
            display: false,
          },
        },
        scales: {
          x: {
            display: true,
            grid: {
              display: false,
              ticks: true,
            },
          },
          y: {
            display: true,
            beginAtZero: true,
            min: 0.0,
            max: 0.5,
            ticks: {
              stepSize: 0.1,
            },
          },
        },
      },
    };
  },
};
</script>

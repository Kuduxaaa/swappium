<script setup>
import Chart from 'chart.js/auto';
</script>

<template>
    <canvas id="myChart"></canvas>
</template>

<script>
export default {
    name: 'ChartjsComponent',

    props: {
        labels: Array,
        dataset: Array
    },

    data() {
        return {
            data: [21803.92, 21625.73, 22088.57, 21439.33],
            data_labels: ['Open', 'Last', 'High', 'Low']
        }
    },

    mounted() {
        const ctx = document.getElementById('myChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: this.data_labels,
                datasets: [
                    {
                        data: this.data,
                        fill: false,
                        pointRadius: 0,
                        spanGaps: true,
                        tension: 0.2
                    },
                ],
            },
            options: {
                events: [],
                borderColor: (this.data[this.data.length - 1] > this.data[this.data.length - 2]) ? '#32a852' : '#db3939',
                borderWidth: 1.5,
                responsive: false,
                plugins: {
                    legend: {
                        display: false,
                        labels: {
                            display: false
                        }
                    },
                    tooltips: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        display: false,
                    },
                    y: {
                        display: false,
                    }
                },
            },
        });
    }
}
</script>

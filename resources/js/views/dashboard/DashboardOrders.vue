<template>
    <div class="dashboard">
        <aside>
            <SidebarComponent />
        </aside>

        <main>
            <div class="ptitle">
                <h3>Your orders</h3>
                <p class="text-secondary">Here is your orders history</p>
            </div>

            <div class="table-responsive tbl">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Created at</th>
                            <th>Amount</th>
                            <th>Fee</th>
                            <th>Type</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="each in history">
                            <td>{{ each.uniqueId }}</td>
                            <td>{{ each.created_at }}</td>
                            <td>{{ applyPercentageReduction(each.amount, 1.1) }}</td>
                            <td>1.1%</td>
                            <td>{{ each.method }}</td>
                            <td>{{ each.status }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</template>

<script setup>
import SidebarComponent from '../../components/dashboard/SidebarComponent.vue';
</script>

<script>
export default {
    name: 'DashboardOrders',
    components: {
        SidebarComponent
    },

    data() {
        return {
            history: null
        }
    },

    methods: {
        getHistory() {
            this.$api.getHistory().then(response => {
                this.history = response;
            });
        },
        
        formatDateFromUnixTimestamp(timestamp) {
            const date = new Date(timestamp * 1000);
            const options = {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                timeZone: 'Asia/Tbilisi',
                timeZoneName: 'short'
            };
            const formatter = new Intl.DateTimeFormat('en-US', options);
            return formatter.format(date);
        },

        applyPercentageReduction(number, percentage) {
            const decimal = percentage / 100;
            const result = number - (number * decimal);

            return result;
        },


        getStatus(code) {
            let out;
            let statuses = {
                pending: [1, 2, 6, 10, 11, 12, 13, 14, 15, 16, 17],
                success: [3, 7],
                cancled: [4, 9],
                unconfirmed: [5],
                partiallySuccessful: [18],
                uncredited: [22],
            };

            for (let keys in statuses) {
                let arr = statuses[keys];

                if (arr.includes(code)) {
                    out = keys.toString().charAt(0).toUpperCase() + keys.toString().slice(1);

                    break;

                } else {
                    console.log(arr, code);
                }
            }

            return out;
        }
    },

    mounted() {
        this.getHistory();
    }
}
</script>

<style scoped>
header {
    padding-bottom: 36px;
}

main {
    padding: 40px 40px;
}

.tbl {
    margin-top: 52px;
}

@media only screen and (max-width: 600px) {
    main {
        padding: 30px 12px;
    }
}
</style>
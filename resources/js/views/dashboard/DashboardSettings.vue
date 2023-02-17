<template>
    <div class="dashboard">
        <aside>
            <SidebarComponent />
        </aside>

        <main>
            <h1>DashboardSettings</h1>
        </main>
    </div>
</template>

<script setup>
import SidebarComponent from '../../components/dashboard/SidebarComponent.vue';
</script>

<script>
export default {
    name: 'DashboardSettings',
    components: {
        SidebarComponent
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


@media only screen and (max-width: 600px) {
    main {
        padding: 30px 12px;
    }
}
</style>
<template>
    <details class="dropdown dropdown-end">
        <summary tabindex="0" role="button" class="btn btn-ghost btn-circle">
            <div class="indicator">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <span class="badge badge-sm badge-neutral indicator-item">{{ count }}</span>
            </div>
        </summary>
        <div tabindex="0" class="mt-3 z-[2] card card-compact border bg-base-100 dropdown-content w-96 shadow">
            <div class="card-body">
                <button v-if="notifications.length" @click="readAll" class="btn btn-outline btn-block btn-neutral">Прочитати всі</button>
                <div role="alert" v-if="notifications.length" class="alert bg-base-300 border shadow-lg"
                     v-for="notification in notifications" :key="notification.id">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         class="stroke-info shrink-0 w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <div class="text-xs">{{ notification.body }}</div>
                    </div>
                </div>

                <div v-else class="p-4 rounded-lg bg-base-300 shadow-lg">
                    <div>
                        <p class="text-center">Немає Нових Сповіщень</p>
                    </div>
                </div>
            </div>
        </div>
    </details>
</template>
<script>
export default {
    data() {
        return {
            notifications: [],
            count: 0,
            user: this.$store.state.user,
        }
    },
    mounted() {

        if (!this.user) {
            return;
        }

        this.fetchNotifications();
    },
    methods: {
        readAll(){
            axios.post('/api/notifications/read-all')
                .then(response => {
                    this.notifications = [];
                    this.count = 0;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        fetchNotifications() {
            axios.get('/api/notifications')
                .then(response => {
                    this.notifications = response.data.data;
                    this.count = this.notifications.length;
                })
                .catch(error => {
                    console.log(error);
                });
        }
    }
}
</script>

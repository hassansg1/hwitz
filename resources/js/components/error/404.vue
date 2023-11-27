<template>
    <div class="content-wrapper">
        <div class="content text-center">
            <div class="error-page">
                <h2 class="headline"> 404</h2>
            </div><br><br><br><br><br><br>

            <div class="error-content">
                <h3><i class="fas fa-exclamation-triangle"></i> Oops! Page not found.</h3>
                <p>
                    We could not find the page you were looking for.<br>
                    Meanwhile, you may <router-link to="/dashboard">return to dashboard</router-link>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
<style scoped>
    h2 {
        color: #1e5598;
        margin-left: 210px;
    }
    h3 {
        color: #1e5598;
    }
    .content {
        margin-top: 88px;
    }
</style>

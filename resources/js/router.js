import Vue from "vue";
import VueRouter from "vue-router";

import Home from "./pages/Home.vue";
import PortfolioOwners from "./pages/AdminDashboard.vue";
import About from "./pages/About.vue";
import UrgentTask from "./pages/UrgentTasks";
import AllLogs from "./pages/ALlLogs.vue";
import Search from "./pages/Search";
import EditProfile from "./pages/EditProfile";
import Messages from "./pages/Messages.vue";
import Building from "./pages/Building.vue";
import NonPerformance from "./pages/NonPerformance.vue";
import Financial from "./pages/Financial.vue";
import Analytics from "./pages/Analytics.vue";
import Settings from "./pages/Settings.vue";
import Staff from "./pages/Staff.vue";
import Storage from "./pages/Storage.vue";
import Offerings from "./pages/Offerings.vue";
import Parking from "./pages/Parking.vue";
import FOBs from "./pages/FOBs.vue";
import Units from "./pages/Units.vue";
import Unit from "./components/units/unit.vue";
import ResidentsList from "./pages/residents/List.vue";
import ResidentsDetails from "./pages/residents/Details.vue";
import AllCameras from "./pages/cameras/all-camera.vue";
import CameraDetail from "./pages/cameras/detail-camera.vue";
import CameraList from "./pages/cameras/list-camera.vue";
import Dashboard from "./pages/Dashboard.vue";
import Packages from "./pages/Packages.vue";
import Laundry from "./pages/Laundry.vue";
import DigitalSignage from "./pages/DigitalSignage.vue";

import Doors from "./pages/Doors.vue";

import Notifications from "./pages/Notifications.vue";
import StaffDetail from "./pages/StaffDetail.vue";
import AddStaffUser from "./components/staff/add-staff-user.vue";
import EditStaffUser from "./components/staff/edit-staff-user.vue";
import Documents from "./pages/Documents.vue";
import UnAuthorized from "./components/error/403.vue";

Vue.use(VueRouter);
import Gate from "./Gate";

let gate = new Gate(window.vueAuth);
let permissions = gate.getAuthRolesPermissions().permissions ?? [];

const router = new VueRouter({
    mode: "history",
    // linkExactActiveClass: 'active',
    routes: [
        {
            path: "/",
            name: "Dashboard",
            component: Dashboard,
        },
        {
            path: "/about",
            name: "about",
            component: About,
        },
        {
            path: "/portfolio_owners",
            name: "Portfolio Owners",
            component: permissions.includes('portfolio_owners') ? PortfolioOwners : UnAuthorized,
        },
        {
            path: "/tasks-work-orders",
            name: "Tasks / Work Orders",
            component: permissions.includes('tasks') ? UrgentTask : UnAuthorized,
        },
        {
            path: "/settings",
            name: "Settings",
            component: permissions.includes('owner_portal_settings') ? Settings : UnAuthorized,
        },
        {
            path: "/all-logs",
            name: "All Logs",
            component: permissions.includes('all_logs') ? AllLogs : UnAuthorized,
        },
        {
            path: "/search",
            name: "search",
            component: permissions.includes('universal_search') ? Search : UnAuthorized,
        },
        {
            path: "/edit-profile",
            name: "edit-profile",
            component: EditProfile,
        },
        {
            path: "/portfolio",
            name: "portfolio",
            component: permissions.includes('buildings_owner_portal') ? Building : UnAuthorized,
        },
        {
            path: "/non-performance",
            name: "non performance",
            component: permissions.includes('non_performance') ? NonPerformance : UnAuthorized,
        },
        {
            path: "/messages",
            name: "messages",
            component: permissions.includes('messages') ? Messages : UnAuthorized,
        },

        {
            path: "/financial",
            name: "financial",
            component: permissions.includes('financials') ? Financial : UnAuthorized,
        },
        {
            path: "/analytics",
            name: "analytics",
            component: permissions.includes('analytics') ? Analytics : UnAuthorized,
        },
        {
            path: "/staff",
            name: "Staff",
            component: permissions.includes('staff') ? Staff : UnAuthorized,
        },
        {
            path: "/notifications",
            name: "Notifications",
            component: permissions.includes('notifications') ? Notifications : UnAuthorized,
        },
        {
            path: "/staff-detail/:id",
            name: "Staff Detail",
            component: permissions.includes('staff') ? StaffDetail : UnAuthorized,
        },
        {
            path: "/unit-detail/:id/:building_id?",
            name: "Unit Detail",
            component: permissions.includes('units') ? Unit : UnAuthorized,
        },
        {
            path: "/add-staff-user/:id",
            name: "Add Staff User",
            component: permissions.includes('staff') ? AddStaffUser : UnAuthorized,
        },
        {
            path: "/edit-staff-user/:id/:userId",
            name: "Edit Staff User",
            component: permissions.includes('staff') ? EditStaffUser : UnAuthorized,
        },

        // below all are building routes

        {
            path: "/storages",
            name: "Storage",
            component: permissions.includes('storage') ? Storage : UnAuthorized,
        },

        {
            path: "/offerings",
            name: "Offerings",
            component: permissions.includes('offerings') ? Offerings : UnAuthorized,
        },

        {
            path: "/parking",
            name: "Parking",
            component: permissions.includes('parking') ? Parking : UnAuthorized,
        },

        {
            path: "/fobs",
            name: "FOBs",
            component: permissions.includes('fobs') ? FOBs : UnAuthorized,
        },
        {
            path: "/units",
            name: "Units",
            component: permissions.includes('units') ? Units : UnAuthorized,
        },
        {
            path: "/residents-list",
            name: "Residents list",
            component: permissions.includes('residents') ? ResidentsList : UnAuthorized,
        },
        {
            path: "/residents-detail",
            name: "Residents-detail",
            component: permissions.includes('residents') ? ResidentsDetails : UnAuthorized,
        },

        {
            path: "/cameras",
            name: "cameras",
            component: permissions.includes('cameras') ? AllCameras : UnAuthorized,
        },
        {
            path: "/camera/:id",
            name: "camera detail",
            component: permissions.includes('cameras') ? CameraDetail : UnAuthorized,
        },
        {
            path: "/camera/:id/:time",
            name: "camera list",
            component: permissions.includes('cameras') ? CameraList : UnAuthorized,
        },

        {
            path: "/doors",
            name: "Doors",
            component: permissions.includes('doors') ? Doors : UnAuthorized,
        },
        {
            path: "/dashboard",
            name: "Dashboard",
            component: Dashboard,
        },
        {
            path: "/packages",
            name: "Packages",
            component: permissions.includes('packages') ? Packages : UnAuthorized,
        },
        {
            path: "/laundry",
            name: "Laundry",
            component: permissions.includes('laundry') ? Laundry : UnAuthorized,
        },

        {
            path: "/digital-signage",
            name: "Digital Signage",
            component: permissions.includes('digital_signage') ? DigitalSignage : UnAuthorized,
        },

        {
            path: "/documents",
            name: "Documents",
            component: permissions.includes('documents') ? Documents : UnAuthorized,
        },
    ],
});

export default router;

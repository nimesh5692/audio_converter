import Home from "../components/Home.vue";
import Login from "../components/Login.vue";
import Register from "../components/Register.vue";

export default [
    { path: "/", component: Home },
    { path: "/home", component: Home },
    { path: "/login", component: Login },
    { path: "/register", component: Register }
];

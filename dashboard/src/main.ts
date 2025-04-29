import { createApp } from 'vue'
import './assets/index.css'
import App from './App.vue'
import router from './router'
import {VueQueryPlugin} from "@tanstack/vue-query"
import { createPinia } from 'pinia'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'

const pinia = createPinia()
pinia.use(piniaPluginPersistedstate)

const app = createApp(App)

app.use(router)
app.use(pinia)
app.use(VueQueryPlugin)
app.mount('#app')
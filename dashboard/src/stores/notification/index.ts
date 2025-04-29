import {defineStore} from "pinia"

type TNotification = {
    type: 'success' | 'error' | 'warning',
    message: string,
} | null

export const useNotification = defineStore('notification', {
    state: () => ({
        pendingNotifications: null as TNotification,
    }),
    actions: {
        setPendingNotifications(notifications: TNotification) {
            this.pendingNotifications = notifications
        }
    },
    getters: {
        getNotification(): TNotification{
            return this.pendingNotifications
        }
    }
})
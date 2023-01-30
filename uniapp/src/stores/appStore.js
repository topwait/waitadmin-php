import { defineStore } from 'pinia'

export const useAppStore = defineStore({
    id:  'appStore',
    state: () => {
        return {
            config: {aa: 'cc'}
        }
    },
    getters: {

    },
    actions: {

    }
})

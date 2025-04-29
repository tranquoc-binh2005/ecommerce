<script setup lang="ts">
  import {watch} from "vue"
  import {Toaster} from "@/components/ui/toast"
  import {useToast} from "@/components/ui/toast"
  import {useNotification} from "@/stores/notification"

  const {toast} = useToast()
  const notification = useNotification()

  const variantMap = {
    success: "default",
    warning: "default",
    error: "destructive",
  } as const

  const classMap = {
    success: "border-green-500 bg-green-100",
    warning: "border-orange-500 bg-orange-100",
    error: "",
  } as const

  const titleMap = {
    success: "Thành công",
    warning: "Cảnh báo hệ thống",
    error: "Có vấn đề xảy ra"
  } as const

  watch(
      () => notification.getNotification,
      (newNotification) => {
          if(newNotification){
            const type = newNotification.type
            toast({
              title: titleMap[type],
              description: newNotification.message,
              variant: variantMap[type],
              class: classMap[type],
            })
          }
          notification.setPendingNotifications(null)
      }
  )
</script>

<template>
  <router-view v-slot="{Component, route}">
    <keep-alive>
      <component :is="Component" :key="route.fullPath"></component>
    </keep-alive>
  </router-view>
  <Toaster position="top-right" />
</template>
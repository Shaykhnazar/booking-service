<template>
    <div
        class="bg-white rounded-lg shadow-md p-6 cursor-pointer hover:shadow-lg transition-shadow border-2"
        :class="{ 'border-blue-500': selected, 'border-transparent': !selected }"
        @click="$emit('select')"
    >
        <h3 class="text-xl font-semibold text-gray-800 mb-2">
            {{ service.name }}
        </h3>
        <p v-if="service.description" class="text-gray-600 mb-4">
            {{ service.description }}
        </p>
        <div class="flex items-center text-sm text-gray-500">
            <i class="pi pi-clock mr-2"></i>
            <span>{{ service.duration_minutes }} минут</span>
        </div>
    </div>
</template>

<script setup lang="ts">
import type { Service } from '@/types';

defineProps<{
    service: Service;
    selected?: boolean;
}>();

defineEmits<{
    select: [];
}>();
</script>

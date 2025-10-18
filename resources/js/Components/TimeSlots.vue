<template>
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">
            Доступные слоты
        </h3>

        <div v-if="loading" class="text-center py-8">
            <i class="pi pi-spin pi-spinner text-4xl text-blue-500"></i>
            <p class="mt-4 text-gray-600">Загрузка доступных слотов...</p>
        </div>

        <div v-else-if="error" class="text-center py-8">
            <i class="pi pi-exclamation-circle text-4xl text-red-500"></i>
            <p class="mt-4 text-red-600">{{ error }}</p>
        </div>

        <div v-else-if="slots.length === 0" class="text-center py-8">
            <i class="pi pi-calendar-times text-4xl text-gray-400"></i>
            <p class="mt-4 text-gray-600">Нет доступных слотов на эту дату</p>
        </div>

        <div v-else class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3">
            <button
                v-for="slot in slots"
                :key="slot.start_time_iso"
                class="py-3 px-4 rounded-lg border-2 transition-colors font-medium"
                :class="getSlotClasses(slot)"
                @click="selectSlot(slot)"
            >
                {{ slot.start_time }}
            </button>
        </div>
    </div>
</template>

<script setup lang="ts">
import type { TimeSlot } from '@/types';

const props = defineProps<{
    slots: TimeSlot[];
    loading: boolean;
    error: string | null;
    selectedSlot?: string;
}>();

const emit = defineEmits<{
    selectSlot: [slot: TimeSlot];
}>();

const getSlotClasses = (slot: TimeSlot): string => {
    if (props.selectedSlot === slot.start_time_iso) {
        return 'bg-blue-500 text-white border-blue-600 hover:bg-blue-600';
    }
    return 'bg-white hover:bg-blue-50 border-gray-300 hover:border-blue-400 text-gray-700';
};

const selectSlot = (slot: TimeSlot) => {
    emit('selectSlot', slot);
};
</script>

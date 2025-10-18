<template>
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Выберите день</h3>

        <div class="grid grid-cols-7 gap-2">
            <div
                v-for="day in weekDays"
                :key="day.date"
                class="text-center p-4 rounded-lg cursor-pointer transition-colors border-2"
                :class="getDayClasses(day)"
                @click="selectDay(day)"
            >
                <div class="text-sm text-gray-600 mb-1">
                    {{ day.dayName }}
                </div>
                <div class="text-2xl font-bold">
                    {{ day.dayNumber }}
                </div>
                <div class="text-xs text-gray-500 mt-1">
                    {{ day.monthName }}
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import dayjs from 'dayjs';
import 'dayjs/locale/ru';
import isSameOrAfter from 'dayjs/plugin/isSameOrAfter';

dayjs.extend(isSameOrAfter);
dayjs.locale('ru');

interface WeekDay {
    date: string;
    dayName: string;
    dayNumber: number;
    monthName: string;
    isSunday: boolean;
    isPast: boolean;
}

const props = defineProps<{
    selectedDate?: string;
}>();

const emit = defineEmits<{
    selectDate: [date: string];
}>();

const weekDays = computed((): WeekDay[] => {
    const today = dayjs();
    const startOfWeek = today.startOf('week');

    return Array.from({ length: 7 }, (_, i) => {
        const day = startOfWeek.add(i, 'day');
        return {
            date: day.format('YYYY-MM-DD'),
            dayName: day.format('dd'),
            dayNumber: day.date(),
            monthName: day.format('MMM'),
            isSunday: day.day() === 0,
            isPast: day.isBefore(today, 'day'),
        };
    });
});

const getDayClasses = (day: WeekDay): string => {
    if (day.isSunday) {
        return 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-200';
    }
    if (day.isPast) {
        return 'bg-gray-50 text-gray-400 cursor-not-allowed border-gray-200';
    }
    if (props.selectedDate === day.date) {
        return 'bg-blue-500 text-white border-blue-600 hover:bg-blue-600';
    }
    return 'bg-white hover:bg-blue-50 border-gray-200 hover:border-blue-300';
};

const selectDay = (day: WeekDay) => {
    if (!day.isSunday && !day.isPast) {
        emit('selectDate', day.date);
    }
};
</script>

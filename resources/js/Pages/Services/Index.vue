<template>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="container mx-auto px-4 max-w-7xl">
            <header class="mb-8 text-center">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">
                    Бронирование услуг
                </h1>
                <p class="text-gray-600">
                    Выберите услугу и удобное время для бронирования
                </p>
            </header>

            <!-- Step 1: Service Selection -->
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">
                    Шаг 1: Выберите услугу
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <ServiceCard
                        v-for="service in services"
                        :key="service.id"
                        :service="service"
                        :selected="selectedService?.id === service.id"
                        @select="selectService(service)"
                    />
                </div>
            </section>

            <!-- Step 2: Date Selection -->
            <section v-if="selectedService" class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">
                    Шаг 2: Выберите дату
                </h2>
                <WeekCalendar
                    :selected-date="selectedDate"
                    @select-date="selectDate"
                />
            </section>

            <!-- Step 3: Time Slot Selection -->
            <section v-if="selectedService && selectedDate" class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">
                    Шаг 3: Выберите время
                </h2>
                <TimeSlots
                    :slots="availableSlots"
                    :loading="loading"
                    :error="error"
                    :selected-slot="selectedSlot"
                    @select-slot="selectSlot"
                />
            </section>

            <!-- Step 4: Booking Form -->
            <section v-if="selectedSlot" class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">
                    Шаг 4: Заполните контактные данные
                </h2>
                <BookingForm
                    :loading="bookingLoading"
                    :error="bookingError"
                    @submit="submitBooking"
                    @cancel="resetBookingForm"
                />
            </section>
        </div>

        <!-- Success Modal -->
        <SuccessModal
            :show="showSuccessModal"
            @close="closeSuccessModal"
        />
    </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';
import { Head } from '@inertiajs/vue3';
import ServiceCard from '@/Components/ServiceCard.vue';
import WeekCalendar from '@/Components/WeekCalendar.vue';
import TimeSlots from '@/Components/TimeSlots.vue';
import BookingForm from '@/Components/BookingForm.vue';
import SuccessModal from '@/Components/SuccessModal.vue';
import { useBooking } from '@/composables/useBooking';
import type { Service, TimeSlot } from '@/types';

interface Props {
    services: Service[];
}

const props = defineProps<Props>();

const selectedService = ref<Service | null>(null);
const selectedDate = ref<string | null>(null);
const selectedSlot = ref<string | null>(null);
const showSuccessModal = ref(false);

const {
    loading,
    error,
    availableSlots,
    fetchAvailableSlots,
    createBooking,
} = useBooking();

const bookingLoading = ref(false);
const bookingError = ref<string | null>(null);

const selectService = (service: Service) => {
    selectedService.value = service;
    selectedDate.value = null;
    selectedSlot.value = null;
};

const selectDate = (date: string) => {
    selectedDate.value = date;
    selectedSlot.value = null;
};

const selectSlot = (slot: TimeSlot) => {
    selectedSlot.value = slot.start_time_iso;
};

const submitBooking = async (formData: { client_name: string; client_phone: string }) => {
    if (!selectedService.value || !selectedSlot.value) return;

    bookingLoading.value = true;
    bookingError.value = null;

    const success = await createBooking({
        service_id: selectedService.value.id,
        client_name: formData.client_name,
        client_phone: formData.client_phone,
        start_time: selectedSlot.value,
    });

    bookingLoading.value = false;

    if (success) {
        showSuccessModal.value = true;
    } else {
        bookingError.value = error.value;
    }
};

const resetBookingForm = () => {
    selectedSlot.value = null;
    bookingError.value = null;
};

const closeSuccessModal = () => {
    showSuccessModal.value = false;
    // Reset to initial state
    selectedService.value = null;
    selectedDate.value = null;
    selectedSlot.value = null;
    bookingError.value = null;
};

// Watch for date changes and fetch available slots
watch([selectedService, selectedDate], ([service, date]) => {
    if (service && date) {
        fetchAvailableSlots(service.id, date);
    }
});
</script>

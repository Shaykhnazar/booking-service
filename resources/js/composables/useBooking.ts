import { ref, Ref } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import type { Service, TimeSlot, BookingForm } from '@/types';

export function useBooking() {
    const loading = ref(false);
    const error: Ref<string | null> = ref(null);
    const availableSlots: Ref<TimeSlot[]> = ref([]);

    const fetchAvailableSlots = async (serviceId: number, date: string) => {
        loading.value = true;
        error.value = null;

        try {
            const response = await axios.get(
                route('bookings.available-slots', { service: serviceId }),
                { params: { date } }
            );
            availableSlots.value = response.data.slots;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Failed to load available slots';
            availableSlots.value = [];
        } finally {
            loading.value = false;
        }
    };

    const createBooking = async (bookingData: BookingForm): Promise<boolean> => {
        loading.value = true;
        error.value = null;

        try {
            await axios.post(route('bookings.store'), bookingData);
            return true;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Failed to create booking';
            return false;
        } finally {
            loading.value = false;
        }
    };

    return {
        loading,
        error,
        availableSlots,
        fetchAvailableSlots,
        createBooking,
    };
}

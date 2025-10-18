<template>
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">
            Контактные данные
        </h3>

        <form @submit.prevent="handleSubmit" class="space-y-4">
            <div>
                <label for="client_name" class="block text-sm font-medium text-gray-700 mb-1">
                    Имя <span class="text-red-500">*</span>
                </label>
                <input
                    id="client_name"
                    v-model="form.client_name"
                    type="text"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Введите ваше имя"
                />
                <p v-if="errors?.client_name" class="mt-1 text-sm text-red-600">
                    {{ errors.client_name }}
                </p>
            </div>

            <div>
                <label for="client_phone" class="block text-sm font-medium text-gray-700 mb-1">
                    Телефон <span class="text-red-500">*</span>
                </label>
                <input
                    id="client_phone"
                    v-model="form.client_phone"
                    type="tel"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="+7 (999) 123-45-67"
                />
                <p v-if="errors?.client_phone" class="mt-1 text-sm text-red-600">
                    {{ errors.client_phone }}
                </p>
            </div>

            <div v-if="error" class="p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-sm text-red-600">{{ error }}</p>
            </div>

            <div class="flex gap-3">
                <button
                    type="button"
                    @click="$emit('cancel')"
                    class="flex-1 px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors"
                >
                    Отмена
                </button>
                <button
                    type="submit"
                    :disabled="loading"
                    class="flex-1 px-6 py-3 bg-blue-500 text-white rounded-lg font-medium hover:bg-blue-600 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed"
                >
                    <span v-if="!loading">Забронировать</span>
                    <span v-else class="flex items-center justify-center">
                        <i class="pi pi-spin pi-spinner mr-2"></i>
                        Бронирование...
                    </span>
                </button>
            </div>
        </form>
    </div>
</template>

<script setup lang="ts">
import { reactive } from 'vue';

interface FormData {
    client_name: string;
    client_phone: string;
}

interface FormErrors {
    client_name?: string;
    client_phone?: string;
}

defineProps<{
    loading: boolean;
    error: string | null;
    errors?: FormErrors;
}>();

const emit = defineEmits<{
    submit: [data: FormData];
    cancel: [];
}>();

const form = reactive<FormData>({
    client_name: '',
    client_phone: '',
});

const handleSubmit = () => {
    emit('submit', { ...form });
};
</script>

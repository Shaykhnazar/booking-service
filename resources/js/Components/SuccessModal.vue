<template>
    <Teleport to="body">
        <Transition name="modal">
            <div
                v-if="show"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50"
                @click.self="close"
            >
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-8 text-center">
                    <div class="mb-6">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto">
                            <i class="pi pi-check text-3xl text-green-600"></i>
                        </div>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 mb-2">
                        Успешно забронировано!
                    </h2>

                    <p class="text-gray-600 mb-6">
                        Ваше бронирование подтверждено. Мы свяжемся с вами в ближайшее время.
                    </p>

                    <button
                        @click="close"
                        class="w-full px-6 py-3 bg-blue-500 text-white rounded-lg font-medium hover:bg-blue-600 transition-colors"
                    >
                        Закрыть
                    </button>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup lang="ts">
defineProps<{
    show: boolean;
}>();

const emit = defineEmits<{
    close: [];
}>();

const close = () => {
    emit('close');
};
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}

.modal-enter-active .bg-white,
.modal-leave-active .bg-white {
    transition: transform 0.3s ease;
}

.modal-enter-from .bg-white,
.modal-leave-to .bg-white {
    transform: scale(0.9);
}
</style>

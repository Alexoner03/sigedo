<template>
  <Dialog as="div">
    <div class="fixed inset-0 z-10 overflow-y-auto">
      <div class="min-h-screen px-4 text-center">
        <TransitionChild
          as="template"
          enter="duration-300 ease-out"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <DialogOverlay class="fixed inset-0 bg-black bg-opacity-75" />
        </TransitionChild>

        <span class="inline-block h-screen align-middle" aria-hidden="true">
          &#8203;
        </span>

        <TransitionChild
          as="template"
          enter="duration-300 ease-out"
          enter-from="opacity-0 scale-95"
          enter-to="opacity-100 scale-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100 scale-100"
          leave-to="opacity-0 scale-95"
        >
          <div
            class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl"
          >
            <DialogTitle as="h3" class="flex flex-col mb-2">
              <div class="flex justify-between items-center mb-2">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-8 w-8 text-secondary"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                  />
                </svg>
                <span
                  @click="closeModal"
                  class="cursor-pointer text-secondary text-lg font-bold leading-6"
                >
                  X
                </span>
              </div>
              <div class="text-secondary text-lg font-bold leading-6">
                Documento enviado a los revisores
              </div>
            </DialogTitle>
            <div class="flex flex-col">
                <p class="mb-8">Te estaremos avisando por correo sobre el proceso</p>
                <div class="flex justify-end">
                    <jet-button :outline="true">Ir a Historial</jet-button>
                    <jet-button class="ml-4">Cerrar sesión</jet-button>
                </div>
            </div>
          </div>
        </TransitionChild>
      </div>
    </div>
  </Dialog>
</template>

<script>
import JetButton from "@/Jetstream/Button";
import {
  TransitionChild,
  Dialog,
  DialogOverlay,
  DialogTitle,
} from "@headlessui/vue";

export default {
  components: {
    JetButton,
    TransitionChild,
    Dialog,
    DialogOverlay,
    DialogTitle,
  },

  props: {
    status: String,
  },

  emits: ["onClose"],

  setup(props, { emit }) {
    const closeModal = () => {
      emit("onClose");
    };

    return {
      closeModal,
    };
  },
};
</script>

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
            <DialogTitle as="h3" class="flex flex-col mb-4">
              <div class="flex justify-between items-center mb-2">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-6 w-6 text-secondary"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
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
                <span> ¿Olvidaste tu contraseña? </span>
              </div>
            </DialogTitle>
            <div class="mb-4 text-sm text-gray-600">
              Te enviaremos un mail con tu contraseña
            </div>

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
              {{ status }}
            </div>

            <jet-validation-errors class="mb-4" />

            <form @submit.prevent="submit">
              <div>
                <jet-label for="email" value="Ingresa tu correo" />
                <jet-input
                  id="email"
                  type="email"
                  class="mt-1 block w-full"
                  v-model="form.email"
                  required
                  placeholder="correo@estudiosanabria.com"
                  autofocus
                />
              </div>

              <div class="flex items-center justify-end mt-4">
                <jet-button
                  :class="{ 'opacity-25': form.processing }"
                  :disabled="form.processing"
                >
                  Enviar Contraseña
                </jet-button>
              </div>
            </form>
          </div>
        </TransitionChild>
      </div>
    </div>
  </Dialog>
</template>

<script>
import JetButton from "@/Jetstream/Button";
import JetInput from "@/Jetstream/Input";
import JetLabel from "@/Jetstream/Label";
import JetValidationErrors from "@/Jetstream/ValidationErrors";
import {
  TransitionChild,
  Dialog,
  DialogOverlay,
  DialogTitle,
} from "@headlessui/vue";
import { useForm } from "@inertiajs/inertia-vue3";

export default {
  components: {
    JetButton,
    JetInput,
    JetLabel,
    JetValidationErrors,
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

    const submit = () => {
      form.post(route("password.email"));
    };

    const form = useForm({
      email: "",
    });

    return {
      closeModal,
      submit,
      form,
    };
  },
};
</script>

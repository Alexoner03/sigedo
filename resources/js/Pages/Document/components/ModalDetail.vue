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
              <div class="text-secondary text-lg font-bold leading-6">
                Detalle del Documento
              </div>
            </DialogTitle>
            <div class="flex flex-col">
                 <div class="w-full p-4 mb-4">
                        <div class="w-full flex mb-2">
                            <div class="w-1/2">Codigo :</div>
                            <div class="w-1/2">
                                {{ document.codigo }}
                            </div>
                        </div>
                        <div class="w-full flex mb-2">
                            <div class="w-1/2">Fecha de Solicitud :</div>
                            <div class="w-1/2">
                                {{ document.request_date }}
                            </div>
                        </div>
                        <div class="w-full flex mb-2">
                            <div class="w-1/2">Ultima modificacion :</div>
                            <div class="w-1/2">
                                {{ document.updated_at }}
                            </div>
                        </div>
                        <div class="w-full flex mb-2">
                            <div class="w-1/2">Observaciones :</div>
                            <div class="w-1/2">
                                {{ document.observations }}
                            </div>
                        </div>
                        <div class="w-full flex mb-2">
                            <div class="w-1/2">Causales de resolución :</div>
                            <div class="w-1/2">
                                {{ document.resolution }}
                            </div>
                        </div>
                        <div class="w-full flex mb-2">
                            <div class="w-1/2">Objetivos del contrato :</div>
                            <div class="w-1/2">
                                {{ document.objective }}
                            </div>
                        </div>
                        <div class="w-full flex mb-2">
                            <div class="w-1/2">Revisores :</div>
                            <div class="w-1/2">
                                <div
                                    v-for="(user, index) in document.users"
                                    :key="index"
                                >
                                    {{ user.name }}
                                </div>
                            </div>
                        </div>
                    </div>
                <div class="flex justify-end">
                    <jet-button class="ml-4" @click="closeModal">Cerrar</jet-button>
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
    document : Object
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

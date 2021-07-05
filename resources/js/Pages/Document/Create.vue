<template>
    <app-layout>
        <template #header>
            <h2 class="font-light text-4xl text-secondary leading-tight">
                Ingresar Documento
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="w-full flex justify-between flex-wrap">

                    <div class="w-1/2 p-4">
                        <jet-label
                            for="codigo"
                            value="Codigo de Documento"
                            class="mb-2"
                        />
                        <jet-input
                            v-model="codigo"
                            id="codigo"
                            class="w-full"
                            type="text"
                            placeholder="XXXX YYY ZZZZ"
                        />
                    </div>

                    <div class="w-1/2 p-4">
                        <jet-label
                            for="fecha_solicitud"
                            value="Fecha de Solicitud"
                            class="mb-2"
                        />
                        <v-date-picker
                            class="inline-block w-full"
                            v-model="date"
                        >
                            <template v-slot="{ inputValue, togglePopover }">
                                <div class="flex items-center">
                                    <jet-input
                                        id="fecha_solicitud"
                                        class="w-full rounded-r"
                                        type="text"
                                        placeholder="XXXX YYY ZZZZ"
                                        :value="inputValue"
                                        readonly
                                        @focus="togglePopover()"
                                    />

                                    <button
                                        class="
                                            p-2
                                            bg-secondary
                                            hover:bg-primary
                                            hover:text-secondary
                                            text-coral
                                            rounded-r
                                            focus:bg-primary
                                            focus:text-secondary
                                            focus:border-secondary
                                            focus:outline-none
                                            transition
                                        "
                                        @click="togglePopover()"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20"
                                            class="w-6 h-6 fill-current"
                                        >
                                            <path
                                                d="M1 4c0-1.1.9-2 2-2h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4zm2 2v12h14V6H3zm2-6h2v2H5V0zm8 0h2v2h-2V0zM5 9h2v2H5V9zm0 4h2v2H5v-2zm4-4h2v2H9V9zm0 4h2v2H9v-2zm4-4h2v2h-2V9zm0 4h2v2h-2v-2z"
                                            ></path>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </v-date-picker>
                    </div>

                    <div class="w-full p-4">
                        <jet-label
                            for="email"
                            value="Correo electrónico"
                            class="mb-2"
                        />
                        <jet-input
                            v-model="email"
                            id="email"
                            class="w-full"
                            type="email"
                            placeholder="correo@estuduiosanabria.com"
                        />
                    </div>

                    <div class="w-full p-4">
                        <p class="mb-2">Ingresa a los revisores en orden de prioridad</p>

                        <Listbox v-model="selectedPerson">
                            <div class="relative mt-1">
                                <ListboxButton
                                    class="
                                        relative
                                        w-full
                                        shadow-sm
                                        py-3
                                        pl-3
                                        pr-10
                                        text-left
                                        bg-transparent
                                        rounded-md
                                        border
                                        border-metalgray
                                        cursor-default
                                        focus:outline-none
                                        focus-visible:ring-2
                                        focus-visible:ring-opacity-75
                                        focus-visible:ring-white
                                        focus-visible:ring-offset-secondary
                                        focus-visible:ring-offset-2
                                        focus-visible:border-primary
                                        sm:text-sm
                                    "
                                >
                                    <span class="block truncate">{{
                                        selectedPerson.name
                                    }}</span>
                                    <span
                                        class="
                                            absolute
                                            inset-y-0
                                            right-0
                                            flex
                                            items-center
                                            pr-2
                                            pointer-events-none
                                        "
                                    >
                                        <SelectorIcon
                                            class="w-5 h-5 text-gray-400"
                                            aria-hidden="true"
                                        />
                                    </span>
                                </ListboxButton>

                                <transition
                                    leave-active-class="transition duration-100 ease-in"
                                    leave-from-class="opacity-100"
                                    leave-to-class="opacity-0"
                                >
                                    <ListboxOptions
                                        class="
                                            absolute
                                            w-full
                                            py-1
                                            mt-1
                                            overflow-auto
                                            text-base
                                            bg-white
                                            rounded-md
                                            shadow-lg
                                            max-h-60
                                            ring-1 ring-black ring-opacity-5
                                            focus:outline-none
                                            sm:text-sm
                                        "
                                    >
                                        <ListboxOption
                                            v-slot="{ active, selected }"
                                            v-for="person in people"
                                            :key="person.name"
                                            :value="person"
                                            as="template"
                                        >
                                            <li
                                                :class="[
                                                    active
                                                        ? 'text-secondary bg-coral'
                                                        : 'text-primary',
                                                    'cursor-default select-none relative py-2 pl-10 pr-4 hover:bg-primary transition',
                                                ]"
                                            >
                                                <span
                                                    :class="[
                                                        selected
                                                            ? 'font-medium'
                                                            : 'font-normal',
                                                        'block truncate',
                                                    ]"
                                                    >{{ person.name }}</span
                                                >
                                                <span
                                                    v-if="selected"
                                                    class="
                                                        absolute
                                                        inset-y-0
                                                        left-0
                                                        flex
                                                        items-center
                                                        pl-3
                                                        text-amber-600
                                                    "
                                                >
                                                    <CheckIcon
                                                        class="w-5 h-5"
                                                        aria-hidden="true"
                                                    />
                                                </span>
                                            </li>
                                        </ListboxOption>
                                    </ListboxOptions>
                                </transition>
                            </div>
                        </Listbox>

                        <p class="ml-4 mt-4 text-secondary cursor-pointer" @click="addReviewer">
                            + Agregar correo
                        </p>

                        <p class="w-full p-4">
                            <ul>
                                <li class="cursor-pointer flex items-center" @click="deleteReviewer(index)" v-for="(reviewer,index) in reviewers" :key="reviewer.id">{{ index + 1 }}.-  {{reviewer.name}} <XIcon class="ml-2 h-4 w-4 text-red-500 cursor-pointer"/></li>
                            </ul>
                        </p>
                    </div>

                    <div class="w-full p-4">
                        <jet-label
                            for="file"
                            value="Subir Documento"
                            class="mb-2"
                        />
                        <div v-bind="getRootProps()" class="w-full border-2 border-dashed rounded-md border-metalgray flex justify-center items-center h-40">
                            <input v-bind="getInputProps()" type="file" accept=".xlsx,.docx,.pptx,application/pdf,officedocument.presentationml.presentation,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/vnd.openxmlformats-officedocument.wordprocessingml.documents">
                            <div class="w-full flex flex-col items-center justify-center">
                                <UploadIcon class="w-8 h-8 mb-2 text-secondary"/>
                                <p class="text-secondary" v-if="isDragActive">Suelta tu archivo aquí</p>
                                <p class="text-secondary" v-else>Arrastra tu documento o dale click aquí</p>
                                <small>Peso máximo 3mb</small>
                                <small v-if="isDragReject" class="text-red-500 text-md">Este Archivo no será admitido</small>
                                <small v-if="isDragAccept" class="text-green-500 text-md">Este Archivo será admitido</small>
                            </div>
                        </div>
                        <div class="py-4" v-if="acceptedFiles.length > 0">
                            <p class="mb-2">Archivo a subir: </p>
                            <div class="flex">{{acceptedFiles[0].name}} <XIcon class="ml-4 h-6 w-6 text-red-500 cursor-pointer" @click="clearFile"/> </div>
                        </div>
                    </div>

                    <div class="w-full py-4 flex justify-center">
                        <jet-button class="text-lg" @click="toggleModalSuccess">Enviar Documentos</jet-button>
                    </div>

                </div>
            </div>
        </div>
        <transition-root appear :show="openModal" as="template">
            <modal-success @onClose="toggleModalSuccess" />
        </transition-root>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import JetLabel from "@/Jetstream/Label";
import JetInput from "@/Jetstream/Input";
import JetButton from "@/Jetstream/Button";
import ModalSuccess from "@/Pages/Document/components/ModalSuccess";
import {
  Listbox,
  ListboxLabel,
  ListboxButton,
  ListboxOptions,
  ListboxOption,
  TransitionRoot,
} from "@headlessui/vue";
import { CheckIcon, SelectorIcon } from "@heroicons/vue/solid";
import { UploadIcon, XIcon } from "@heroicons/vue/outline";
import { useDropzone } from "vue3-dropzone";
import { reactive, ref } from "vue";

export default {
  name: "DocumentIndex",
  components: {
    AppLayout,
    JetLabel,
    JetInput,
    JetButton,
    Listbox,
    ListboxLabel,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
    CheckIcon,
    SelectorIcon,
    UploadIcon,
    XIcon,
    TransitionRoot,
    ModalSuccess,
  },
  setup() {
    const date = reactive(new Date());
    const codigo = ref(null);
    const email = ref(null);
    const reviewers = ref([]);
    const file = ref(null);
    const openModal = ref(false);
    const people = [
      { id: 1, name: "Durward Reynolds", unavailable: false },
      { id: 2, name: "Kenton Towne", unavailable: false },
      { id: 3, name: "Therese Wunsch", unavailable: false },
      { id: 4, name: "Benedict Kessler", unavailable: true },
      { id: 5, name: "Katelyn Rohan", unavailable: false },
    ];
    const selectedPerson = ref(people[0]);
    const { getRootProps, getInputProps, ...rest } = useDropzone({
      accept: [
        ".xlsx",
        ".docx",
        ".pptx",
        "application/pdf",
        "officedocument.presentationml.presentation",
        "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        "application/vnd.openxmlformats-officedocument.presentationml.presentation",
        "application/vnd.openxmlformats-officedocument.wordprocessingml.documents",
        "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
      ],
    });

    const addReviewer = () => {
      const exist = reviewers.value.findIndex(
        (elm) => elm.id === selectedPerson.value.id
      );

      if (exist === -1) {
        reviewers.value.push(selectedPerson.value);
      }
    };

    const deleteReviewer = (index) => {
      reviewers.value.splice(index, 1);
    };

    const clearFile = () => {
      rest.acceptedFiles.value = [];
    };

    const toggleModalSuccess = () => {
      openModal.value = !openModal.value;
    };

    return {
      openModal,
      toggleModalSuccess,
      date,
      codigo,
      email,
      reviewers,
      people,
      selectedPerson,
      addReviewer,
      file,
      getRootProps,
      getInputProps,
      clearFile,
      deleteReviewer,
      ...rest,
    };
  },
};
</script>

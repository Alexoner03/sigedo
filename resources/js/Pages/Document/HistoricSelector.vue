<template>
  <app-layout>
    <template #header>
      <h2 class="font-light text-4xl text-secondary leading-tight">
        Historial de Documentos
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="w-full flex flex-col justify-center">
          <div class="w-1/3 mx-auto flex flex-col justify-center items-center">
            <p class="w-full">Clientes</p>
            <div class="w-full flex items-center">
              <Listbox v-model="selectedPerson" class="flex-grow">
                <div class="relative mt-1">
                  <ListboxButton
                    class="relative w-full shadow-sm py-3 pl-3 pr-10 text-left bg-transparent rounded-md border border-metalgray cursor-default focus:outline-none focus-visible:ring-2 focus-visible:ring-opacity-75 focus-visible:ring-white focus-visible:ring-offset-secondary focus-visible:ring-offset-2 focus-visible:border-primary sm:text-sm"
                  >
                    <span class="block truncate">{{
                      selectedPerson.name
                    }}</span>
                    <span
                      class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none"
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
                      class="absolute w-full py-1 mt-1 overflow-auto text-base bg-white rounded-md shadow-lg max-h-60 ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
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
                            active ? 'text-secondary bg-coral' : 'text-primary',
                            'cursor-default select-none relative py-2 pl-10 pr-4 hover:bg-primary transition',
                          ]"
                        >
                          <span
                            :class="[
                              selected ? 'font-medium' : 'font-normal',
                              'block truncate',
                            ]"
                            >{{ person.name }}</span
                          >
                          <span
                            v-if="selected"
                            class="absolute inset-y-0 left-0 flex items-center pl-3 text-amber-600"
                          >
                            <CheckIcon class="w-5 h-5" aria-hidden="true" />
                          </span>
                        </li>
                      </ListboxOption>
                    </ListboxOptions>
                  </transition>
                </div>
              </Listbox>
              <jet-button class="ml-4 h-11 mt-1">Ver Historial</jet-button>
            </div>
          </div>

          <div class="w-full md:w-12/12 mx-auto flex flex-col wrap my-8">
            <!-- table header -->
            <div class="w-full flex bg-primary text-white rounded-md py-2 mb-4">
              <div class="w-1/5 text-center">N° Documento</div>
              <div class="w-1/5 text-center">Estado</div>
              <div class="w-1/5 text-center">Flujo</div>
              <div class="w-1/5 text-center">Hitos</div>
              <div class="w-1/5 text-center">Archivo</div>
            </div>

            <!-- table body -->
            <div class="w-full flex bg-white rounded-md py-2 mb-2">
              <div class="w-1/5 text-center">1234567890</div>
              <div class="w-1/5 text-center">
                En Proceso
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-4 w-4 inline"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                  />
                </svg>
              </div>
              <div class="w-1/5 text-center text-secondary">
                <Menu>
                  <MenuButton class="focus:outline-none"
                    >Ver Estado de Mis Revisiones
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-4 w-4 inline"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 9l-7 7-7-7"
                      /></svg
                  ></MenuButton>
                  <transition
                    enter-active-class="transition duration-100 ease-out"
                    enter-from-class="transform scale-95 opacity-0"
                    enter-to-class="transform scale-100 opacity-100"
                    leave-active-class="transition duration-75 ease-out"
                    leave-from-class="transform scale-100 opacity-100"
                    leave-to-class="transform scale-95 opacity-0"
                  >
                    <MenuItems>
                      <MenuItem v-slot="{ active }">
                        <a
                          :class="[
                            active
                              ? 'bg-blue-500 text-white'
                              : 'bg-white text-black',
                          ]"
                          class="w-full flex justify-between py-2"
                          href="/account-settings"
                        >
                          <span>Luis Sanabria</span>
                          <span>check</span>
                        </a>
                      </MenuItem>
                      <MenuItem v-slot="{ active }">
                        <a
                          :class="[
                            active
                              ? 'bg-blue-500 text-white'
                              : 'bg-white text-black',
                          ]"
                          class="w-full flex justify-between py-2"
                          href="/account-settings"
                        >
                          <span>Luis Sanabria</span>
                          <span>check</span>
                        </a>
                      </MenuItem>
                    </MenuItems>
                  </transition>
                </Menu>
              </div>
              <div class="w-1/5 text-center text-secondary">
                <Menu>
                  <MenuButton class="focus:outline-none">
                    Ver flujo de fechas
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-4 w-4 inline"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 9l-7 7-7-7"
                      /></svg
                  ></MenuButton>
                  <transition
                    enter-active-class="transition duration-100 ease-out"
                    enter-from-class="transform scale-95 opacity-0"
                    enter-to-class="transform scale-100 opacity-100"
                    leave-active-class="transition duration-75 ease-out"
                    leave-from-class="transform scale-100 opacity-100"
                    leave-to-class="transform scale-95 opacity-0"
                  >
                    <MenuItems>
                      <MenuItem v-slot="{ active }">
                        <a
                          :class="[
                            active
                              ? 'bg-blue-500 text-white'
                              : 'bg-white text-black',
                          ]"
                          class="w-full flex justify-between py-2"
                          href="/account-settings"
                        >
                          <span>Luis Sanabria</span>
                          <span>check</span>
                        </a>
                      </MenuItem>
                      <MenuItem v-slot="{ active }">
                        <a
                          :class="[
                            active
                              ? 'bg-blue-500 text-white'
                              : 'bg-white text-black',
                          ]"
                          class="w-full flex justify-between py-2"
                          href="/account-settings"
                        >
                          <span>Luis Sanabria</span>
                          <span>check</span>
                        </a>
                      </MenuItem>
                    </MenuItems>
                  </transition>
                </Menu>
              </div>
              <div class="w-1/5 text-center text-secondary">
                Descargar Documento
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-4 w-4 inline"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                  />
                </svg>
              </div>
            </div>
          </div>

          <div class="flex justify-center">
            <inertia-link :href="route('document.welcome')"
              ><jet-button :outline="true" class="py-3">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-3 w-3 mr-3"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 19l-7-7 7-7"
                  />
                </svg>
                Volver al Home
              </jet-button>
            </inertia-link>
          </div>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import JetLabel from "@/Jetstream/Label";
import JetInput from "@/Jetstream/Input";
import JetButton from "@/Jetstream/Button";
import { ref } from "vue";
import {
  Listbox,
  ListboxLabel,
  ListboxButton,
  ListboxOptions,
  ListboxOption,
  Menu,
  MenuButton,
  MenuItems,
  MenuItem,
} from "@headlessui/vue";
import { CheckIcon, SelectorIcon } from "@heroicons/vue/solid";

export default {
  name: "DocumentHistoricSelector",
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
    Menu,
    MenuButton,
    MenuItems,
    MenuItem,
  },

  setup() {
    const people = [
      { id: 1, name: "Durward Reynolds", unavailable: false },
      { id: 2, name: "Kenton Towne", unavailable: false },
      { id: 3, name: "Therese Wunsch", unavailable: false },
      { id: 4, name: "Benedict Kessler", unavailable: true },
      { id: 5, name: "Katelyn Rohan", unavailable: false },
    ];
    const selectedPerson = ref(people[0]);

    return {
      people,
      selectedPerson,
    };
  },
};
</script>

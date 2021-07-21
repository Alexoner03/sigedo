<template>
    <div class="w-full md:w-12/12 mx-auto flex flex-col wrap my-8">
        <!-- table header -->
        <div class="w-full flex bg-primary text-white rounded-md py-2 mb-4">
            <div class="w-1/6 text-center">N° Documento</div>
            <div class="w-1/6 text-center">Cliente</div>
            <div class="w-1/6 text-center">Estado</div>
            <div class="w-1/6 text-center">Flujo</div>
            <div class="w-1/6 text-center">Hitos</div>
            <div class="w-1/6 text-center">Acciones</div>
        </div>

        <!-- table body -->
        <div
            class="w-full flex bg-white rounded-md py-2 mb-2"
            v-for="(document, index) in documents"
            :key="index"
        >
            <div class="w-1/5 text-center">
                {{ document.codigo }}
            </div>
            <div class="w-1/5 text-center">
                {{ document.client_assigned.name }}
            </div>
            <div class="w-1/5 text-center">
                {{
                    document.state === "finalizado" ? "Archivado" : "En Proceso"
                }}
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
                        >Ver Estados
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
                            <MenuItem
                                v-for="(user, index) in document.users"
                                :key="index + '_'"
                            >
                                <span
                                    class="
                                        w-full
                                        flex
                                        justify-between
                                        py-2
                                        text-black text-xs
                                    "
                                >
                                    <span>{{ user.name }}</span>
                                    <span>{{
                                        user.pivot.check
                                            ? "Aprobado"
                                            : "Pendiente"
                                    }}</span>
                                </span>
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
                            <MenuItem>
                                <span
                                    class="
                                        w-full
                                        flex
                                        justify-between
                                        py-2
                                        text-black text-xs
                                    "
                                >
                                    <span>Creación</span>
                                    <span>{{ document.created_at }}</span>
                                </span>
                            </MenuItem>
                            <MenuItem>
                                <span
                                    class="
                                        w-full
                                        flex
                                        justify-between
                                        py-2
                                        text-black text-xs
                                    "
                                >
                                    <span>Ultima Mod.</span>
                                    <span>{{ document.updated_at }}</span>
                                </span>
                            </MenuItem>
                        </MenuItems>
                    </transition>
                </Menu>
            </div>
            <div class="w-1/5 text-center text-secondary">
                <Menu>
                    <MenuButton class="focus:outline-none">
                        Menu de Acciones
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
                            <MenuItem>
                                <span
                                    class="
                                        w-full
                                        flex
                                        py-2
                                        justify-center
                                        text-sm
                                    "
                                >
                                    <a
                                        :href="`/storage/${document.path}`"
                                        download
                                        >Descargue el archivo</a
                                    >
                                    &nbsp;
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
                                </span>
                            </MenuItem>
                            <MenuItem
                                v-if="
                                    created &&
                                    isAllReviewersChecks(document) &&
                                    isSameUser(document)
                                "
                            >
                                <span
                                    class="
                                        w-full
                                        flex
                                        py-2
                                        justify-center
                                        text-sm
                                    "
                                >
                                    <a
                                        :href="
                                            route('document.observations', {
                                                document: document.id,
                                            })
                                        "
                                        >Revisar Observaciones</a
                                    >
                                    &nbsp;
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
                                </span>
                            </MenuItem>
                            <MenuItem
                                v-if="
                                    created &&
                                    !isAllReviewersChecks(document) &&
                                    isSameUser(document) &&
                                    document.state !== 'finalizado'
                                "
                            >
                                <span
                                    class="
                                        w-full
                                        flex
                                        py-2
                                        justify-center
                                        text-sm
                                    "
                                >
                                    <a
                                        :href="
                                            route('document.contract', {
                                                document: document.id,
                                            })
                                        "
                                        >Finalizar</a
                                    >
                                    &nbsp;
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
                                </span>
                            </MenuItem>
                            <MenuItem
                                v-if="
                                    assigned && $page.props.user.role_id !== 3
                                "
                            >
                                <span
                                    class="
                                        w-full
                                        flex
                                        py-2
                                        justify-center
                                        text-sm
                                    "
                                >
                                    <a
                                        :href="
                                            route('document.show', {
                                                document: document.id,
                                            })
                                        "
                                        >Revisar Documento</a
                                    >
                                    &nbsp;
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
                                </span>
                            </MenuItem>
                            <MenuItem v-if="document.state === 'finalizado'">
                                <span
                                    class="
                                        w-full
                                        flex
                                        py-2
                                        justify-center
                                        text-sm
                                    "
                                >
                                    <a
                                        class="cursor-pointer"
                                        @click="openDocument(document)"
                                        >ver Detalle</a
                                    >
                                    &nbsp;
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
                                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                        />
                                    </svg>
                                </span>
                            </MenuItem>
                        </MenuItems>
                    </transition>
                </Menu>
                <div class="w-full flex flex-col">
                    <span class="w-full"> </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
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
    components: {
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
    props: ["documents", "created", "assigned"],
    emits: ["openDetail"],

    setup(props, { emit }) {
        const isAllReviewersChecks = (document) => {
            return document.users.some((user) => user.pivot.check === 0);
        };

        const isSameUser = (document) => {
            return document.id_user_assigned === document.id_user_creator;
        };

        const openDocument = (document) => emit("openDetail", document);

        return {
            isAllReviewersChecks,
            isSameUser,
            openDocument,
        };
    },
};
</script>

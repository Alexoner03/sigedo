<template>
    <app-layout>
        <template #header>
            <h2 class="font-light text-4xl text-secondary leading-tight">
                Ingresar Revisores
            </h2>
        </template>

        <div class="pb-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <validation-errors/>


                <div class="w-full flex justify-between flex-wrap">
                    <div class="w-full p-4" v-if="businessAuth === 1">
                        <p class="mb-2">Selecciona una empresa</p>

                        <select @change="getEmployees" v-model="selectedBusiness"
                                class="form-select w-full bg-transparent rounded focus:border-secondary focus:ring focus:ring-secondary focus:ring-opacity-50 uppercase">
                            <option :value="businees" v-for="(businees, index) in businesses" :key="index">
                                {{ businees.business_name }}
                            </option>
                        </select>

                    </div>

                    <div class="w-full p-4" v-if="businessAuth === 1">
                        <p class="mb-2">Selecciona un Area</p>

                        <select @change="getEmployees" v-model="selectedPosition"
                                class="form-select w-full bg-transparent rounded focus:border-secondary focus:ring focus:ring-secondary focus:ring-opacity-50 uppercase">
                            <option :value="position" v-for="(position, index) in positions" :key="index">
                                {{ position.description }}
                            </option>
                        </select>

                    </div>

                    <div class="w-full p-4">
                        <p class="mb-2" v-if="employees.length > 0">
                            {{
                                businessAuth === 1 ? "Seleccione un revisor" : "Selecciona al miembro de Sanabria y Asociados que quieres que revise tu documento"
                            }}
                        </p>

                        <select v-if="employees.length > 0" name="position" v-model="selectedEmployee"
                                class="form-select w-full bg-transparent rounded focus:border-secondary focus:ring focus:ring-secondary focus:ring-opacity-50 uppercase">
                            <option :value="employee" v-for="(employee, index) in employees" :key="index">
                                {{ employee.name }}
                            </option>
                        </select>

                        <p v-else>No existe ningun trabajador en esta area</p>

                        <p class="mt-4 text-secondary cursor-pointer" @click="addReviewer">
                            + Agregar Empleado como revisor del Documento
                        </p>

                        <p class="w-full p-4">
                            <ul>
                                <li class="cursor-pointer flex items-center" @click="deleteReviewer(index)"
                                    v-for="(reviewer,index) in form.reviewers" :key="reviewer.id">
                                    {{ index + 1 }}.-
                                    {{ reviewer.name }}
                                    <XIcon class="ml-2 h-4 w-4 text-red-500 cursor-pointer"/>
                                </li>
                            </ul>
                        </p>

                    </div>

                    <div class="w-full p-4">

                        <h2 class="text-center mb-4 font-light text-4xl text-secondary leading-tight">
                            Ingresar Documento
                        </h2>

                        <jet-label
                            for="file"
                            value="Subir Documentos"
                            class="mb-2"
                        />

                        <div v-bind="getRootProps()"
                             class="w-full border-2 border-dashed rounded-md border-metalgray flex justify-center items-center h-40">
                            <input v-bind="getInputProps()" type="file"
                                   accept=".xlsx,.docx,.pptx,application/pdf,officedocument.presentationml.presentation,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/vnd.openxmlformats-officedocument.wordprocessingml.documents">
                            <div class="w-full flex flex-col items-center justify-center">
                                <UploadIcon class="w-8 h-8 mb-2 text-secondary"/>
                                <p class="text-secondary" v-if="isDragActive">Suelta tu archivo aquí</p>
                                <p class="text-secondary" v-else>Arrastra tu documento o dale click aquí</p>
                                <small v-if="isDragReject" class="text-red-500 text-md">Este Archivo no será
                                    admitido</small>
                                <small v-if="isDragAccept" class="text-green-500 text-md">Este Archivo será
                                    admitido</small>
                            </div>
                        </div>
                        <small>*El primer documento será el principal y los siguientes serán guardados como
                            anexos</small>
                        <div class="py-4" v-if="form.files.length > 0">
                            <p class="mb-2">Archivos a subir: </p>

                            <div class="flex" v-for="(acceptedFile,index) in form.files" :key="index">
                                {{ acceptedFile.name }}
                                <XIcon class="ml-4 h-6 w-6 text-red-500 cursor-pointer" @click="clearFile(index)"/>
                            </div>
                        </div>
                        <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                            {{ form.progress.percentage }}%
                        </progress>
                    </div>

                    <div class="w-full py-4 flex justify-center">
                        <jet-button class="text-lg" @click="submitForm">Enviar Documentos</jet-button>
                    </div>

                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import JetLabel from "@/Jetstream/Label";
import JetButton from "@/Jetstream/Button";
import {UploadIcon, XIcon} from "@heroicons/vue/outline";
import {useDropzone} from "vue3-dropzone";
import {ref} from "vue";
import {useForm, usePage} from '@inertiajs/inertia-vue3'
import ValidationErrors from '../../Jetstream/ValidationErrors.vue';
import {computed} from "@vue/runtime-core";


export default {
    name: "DocumentIndex",
    components: {
        AppLayout,
        JetLabel,
        JetButton,
        UploadIcon,
        XIcon,
        ValidationErrors,
    },

    props: ['collaborators', 'clients', 'businesses', 'positions'],

    setup(props) {
        console.log()
        const businessAuth = computed(() => usePage().props.value.user.business_id)
        const selectedBusiness = ref(props.businesses[0])
        const selectedPosition = ref(props.positions[0])
        const selectedEmployee = ref(null)
        const employees = ref([])
        const form = useForm({
            files: [],
            business_id: 0,
            reviewers: [],
        })

        const {getRootProps, getInputProps, ...rest} = useDropzone({
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
            onDropAccepted: (acceptedFiles) => form.files = [...form.files, ...acceptedFiles]
        });


        const getEmployees = async () => {
            const raw = await fetch(route('business.position.users', {
                business: selectedBusiness.value.id,
                position: selectedPosition.value.id
            }))
            employees.value = await raw.json()
            selectedEmployee.value = employees.value[0]
        }

        const addReviewer = () => {
            const exist = form.reviewers.findIndex(
                (elm) => elm.id === selectedEmployee.value.id
            );

            if (exist === -1) {
                form.reviewers.push(selectedEmployee.value);
            }
        };

        const deleteReviewer = (index) => {
            form.reviewers.splice(index, 1);
        };

        const clearFile = (index) => {
            if (index === 'all') {
                rest.acceptedFiles.value = [];
                form.files = []
            } else {
                form.files = form.files.filter((file, _index) => _index !== index)
            }

        };

        const submitForm = async () => {

            form.reviewers = form.reviewers.map(rev => rev.id)
            form.business_id = businessAuth.value === 1 ? selectedBusiness.value.id : businessAuth.value
            form.post(route('contract.store'), {
                onError: () => {
                    form.reset();
                    clearFile('all')
                }
            })

        }

        getEmployees();

        return {

            selectedBusiness,
            selectedPosition,
            selectedEmployee,
            employees,
            form,
            businessAuth,

            getEmployees,
            addReviewer,
            deleteReviewer,
            clearFile,
            submitForm,

            getRootProps,
            getInputProps,
            ...rest,
        };
    },
};
</script>

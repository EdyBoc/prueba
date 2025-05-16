<script setup>
import { ref } from "vue";
import { useForm, router } from "@inertiajs/vue3";

const form = useForm({
    primer_nombre: "",
    segundo_nombre: "",
    primer_apellido: "",
    segundo_apellido: "",
    apellido_casada: "",
    tipo_documento: "cui",
    cui: "",
    pasaporte: "",
    fecha_nacimiento: "",
    telefono: "",
    email: "",
    sexo: "masculino",
    estado_civil: "",
    direccion: "",
    documento: null,
});

const submit = () => {
    form.post("/personas", {
        forceFormData: true,
        onSuccess: () => {
            alert("Persona registrada correctamente");
        },
    });
};
</script>

<template>
    <div class="max-w-3xl mx-auto p-6 bg-white rounded shadow">
        <h1 class="text-2xl font-bold mb-6">Registrar Persona</h1>

        <form
            @submit.prevent="submit"
            enctype="multipart/form-data"
            class="grid grid-cols-2 gap-4"
        >
            <div>
                <label>Primer Nombre</label>
                <input v-model="form.primer_nombre" type="text" class="input" />
                <span class="text-red-500 text-sm">{{
                    form.errors.primer_nombre
                }}</span>
            </div>

            <div>
                <label>Segundo Nombre</label>
                <input
                    v-model="form.segundo_nombre"
                    type="text"
                    class="input"
                />
            </div>

            <div>
                <label>Primer Apellido</label>
                <input
                    v-model="form.primer_apellido"
                    type="text"
                    class="input"
                />
                <span class="text-red-500 text-sm">{{
                    form.errors.primer_apellido
                }}</span>
            </div>

            <div>
                <label>Segundo Apellido</label>
                <input
                    v-model="form.segundo_apellido"
                    type="text"
                    class="input"
                />
                <span class="text-red-500 text-sm">{{
                    form.errors.segundo_apellido
                }}</span>
            </div>

            <div>
                <label>Apellido de Casada</label>
                <input
                    v-model="form.apellido_casada"
                    type="text"
                    class="input"
                />
            </div>

            <div>
                <label>Tipo de Documento</label>
                <select v-model="form.tipo_documento" class="input">
                    <option value="cui">CUI</option>
                    <option value="pasaporte">Pasaporte</option>
                </select>
                <span class="text-red-500 text-sm">{{
                    form.errors.tipo_documento
                }}</span>
            </div>

            <div v-if="form.tipo_documento === 'cui'">
                <label>CUI</label>
                <input v-model="form.cui" type="text" class="input" />
                <span class="text-red-500 text-sm">{{ form.errors.cui }}</span>
            </div>

            <div v-if="form.tipo_documento === 'pasaporte'">
                <label>Pasaporte</label>
                <input v-model="form.pasaporte" type="text" class="input" />
                <span class="text-red-500 text-sm">{{
                    form.errors.pasaporte
                }}</span>
            </div>

            <div>
                <label>Fecha de Nacimiento</label>
                <input
                    v-model="form.fecha_nacimiento"
                    type="date"
                    class="input"
                />
                <span class="text-red-500 text-sm">{{
                    form.errors.fecha_nacimiento
                }}</span>
            </div>

            <div>
                <label>Teléfono</label>
                <input v-model="form.telefono" type="text" class="input" />
                <span class="text-red-500 text-sm">{{
                    form.errors.telefono
                }}</span>
            </div>

            <div>
                <label>Email</label>
                <input v-model="form.email" type="email" class="input" />
                <span class="text-red-500 text-sm">{{
                    form.errors.email
                }}</span>
            </div>

            <div>
                <label>Sexo</label>
                <select v-model="form.sexo" class="input">
                    <option value="masculino">Masculino</option>
                    <option value="femenino">Femenino</option>
                </select>
                <span class="text-red-500 text-sm">{{ form.errors.sexo }}</span>
            </div>

            <div>
                <label>Estado Civil</label>
                <input v-model="form.estado_civil" type="text" class="input" />
                <span class="text-red-500 text-sm">{{
                    form.errors.estado_civil
                }}</span>
            </div>

            <div class="col-span-2">
                <label>Dirección</label>
                <textarea v-model="form.direccion" class="input"></textarea>
                <span class="text-red-500 text-sm">{{
                    form.errors.direccion
                }}</span>
            </div>

            <div class="col-span-2">
                <label>Documento PDF</label>
                <input
                    type="file"
                    @change="(e) => (form.documento = e.target.files[0])"
                    class="input"
                />
                <span class="text-red-500 text-sm">{{
                    form.errors.documento
                }}</span>
            </div>

            <div class="col-span-2 mt-4 flex gap-4">
                <button
                    type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                >
                    Guardar Persona
                </button>

                <button
                    type="button"
                    @click="router.visit('/personas')"
                    class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600"
                >
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</template>

<style scoped>
.input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
}
</style>

<script setup>
import { router } from "@inertiajs/vue3";

defineProps({
    personas: Array,
});

const irACrearPersona = () => {
    router.visit("/personas/crear");
};
</script>

<template>
    <div class="max-w-7xl mx-auto px-6 py-4">
        <h1 class="text-2xl font-bold mb-4">Listado de Personas</h1>

        <div
            v-if="flashSuccess"
            class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4"
        >
            {{ flashSuccess }}
        </div>

        <div class="mb-4">
            <button
                @click="irACrearPersona"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
            >
                + Nueva Persona
            </button>
        </div>

        <table class="min-w-full bg-white border rounded shadow">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="p-2">Nombre</th>
                    <th class="p-2">Documento</th>
                    <th class="p-2">Email</th>
                    <th class="p-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="persona in personas"
                    :key="persona.id"
                    class="border-t"
                >
                    <td class="p-2">
                        {{ persona.primer_nombre }}
                        {{ persona.primer_apellido }}
                    </td>
                    <td class="p-2">
                        {{
                            persona.tipo_documento === "cui"
                                ? persona.cui
                                : persona.pasaporte
                        }}
                    </td>
                    <td class="p-2">{{ persona.email }}</td>
                    <button
                        @click="router.visit(`/personas/${persona.id}/editar`)"
                        class="text-blue-600 hover:underline"
                    >
                        Editar
                    </button>
                </tr>
            </tbody>
        </table>
    </div>
</template>

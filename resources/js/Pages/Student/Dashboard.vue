<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {Table} from "@protonemedia/inertiajs-tables-laravel-query-builder";
import { Link, router } from "@inertiajs/vue3";
import Swal from 'sweetalert2';

const props = defineProps({
    projects: Array
});

const confirmLeave = (project) => {
    Swal.fire({
        title: 'Czy na pewno chcesz opuścić projekt?',
        text: "Nie będziesz mógł cofnąć tej operacji!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Tak, opuść',
        cancelButtonText: 'Anuluj'
    }).then((result) => {
        if (result.isConfirmed) {
            router.visit(route('student.projects.leave', { project }), {
                method: 'get'
            });
        }
    });
};
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Projekty
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
                            <thead class="text-left">
                            <tr>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Nazwa</th>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Opis</th>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Data zakończenia</th>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Akcje</th>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Opuść</th>
                            </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">
                            <tr v-for="project in projects">
                                <td class="whitespace-nowrap px-4 py-2 text-gray-900">{{ project.name }}</td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ project.description }}</td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ project.due_date }}</td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-700"><Link :href="route('student.projects.show',{'project': project})">Przejdź</Link></td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-700"><Link @click.prevent="confirmLeave(project)">Opuść</Link></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

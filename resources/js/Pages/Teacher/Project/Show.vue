<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Table } from "@protonemedia/inertiajs-tables-laravel-query-builder";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    project: Object
})
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
                        <div class="flow-root rounded-lg border border-gray-100 py-3 shadow-sm">
                            <dl class="-my-3 divide-y divide-gray-100 text-sm">
                                <div class="grid grid-cols-1 gap-1 p-3 sm:grid-cols-3 sm:gap-4">
                                    <dt class="font-medium text-gray-900">Nazwa</dt>
                                    <dd class="text-gray-700 sm:col-span-2">{{ project.name }}</dd>
                                </div>

                                <div class="grid grid-cols-1 gap-1 p-3 sm:grid-cols-3 sm:gap-4">
                                    <dt class="font-medium text-gray-900">Opis</dt>
                                    <dd class="text-gray-700 sm:col-span-2">{{ project.description }}</dd>
                                </div>

                                <div class="grid grid-cols-1 gap-1 p-3 sm:grid-cols-3 sm:gap-4">
                                    <dt class="font-medium text-gray-900">Link do dołączenia</dt>
                                    <dd class="text-gray-700 sm:col-span-2">{{ route('join-project',{ project: project }) }}</dd>
                                </div>

                                <div class="grid grid-cols-1 gap-1 p-3 sm:grid-cols-3 sm:gap-4">
                                    <dt class="font-medium text-gray-900">Data oddania</dt>
                                    <dd class="text-gray-700 sm:col-span-2">{{ project.due_date }}</dd>
                                </div>

                                <div class="grid grid-cols-1 gap-1 p-3 sm:grid-cols-3 sm:gap-4">
                                    <dt class="font-medium text-gray-900">Ilość grup</dt>
                                    <dd class="text-gray-700 sm:col-span-2">{{ project.groups }}</dd>
                                </div>

                                <div class="grid grid-cols-1 gap-1 p-3 sm:grid-cols-3 sm:gap-4">
                                    <dt class="font-medium text-gray-900">Ilość studentów</dt>
                                    <dd class="text-gray-700 sm:col-span-2">{{ project.students_count }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div class="flex justify-between items-center m-3">
                            <h1 class="font-bold">Zadania</h1>
                            <a :href="route('teacher.projects.tasks.create',{'project': project})" class="underline">Dodaj nowe zadanie</a>
                        </div>
                        <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
                            <thead class="text-left">
                            <tr>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Nazwa</th>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Szacowana ilość godzin</th>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Student</th>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Akcje</th>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Akcje</th>
                            </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">
                            <tr v-for="task in project['tasks']">
                                <td class="whitespace-nowrap px-4 py-2 text-gray-900">{{ task.name }}</td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ task.hours }}</td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ task.student ?? "Brak" }}</td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-700"><a :href="route('teacher.tasks.edit',{'task': task})">Edytuj</a></td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-700"><a class="cursor-pointer" @click="router.get(route('teacher.tasks.destroy', { 'task': task.id}))">Usuń</a></td>
                            </tr>
                            </tbody>
                        </table>

                        <hr>

                        <div class="flex justify-between items-center m-3">
                            <h1 class="font-bold">Studenci</h1>
                        </div>
                        <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
                            <thead class="text-left">
                            <tr>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Imię i nazwisko</th>
                            </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">
                            <tr v-for="student in project['students']">
                                <td>
                                    {{ student.groups.find((group) => group.project_id === project.id).pivot.group_number }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-900">{{ student.name }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

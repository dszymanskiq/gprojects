<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Table } from "@protonemedia/inertiajs-tables-laravel-query-builder";
import {router, Link} from "@inertiajs/vue3";
import Swal from "sweetalert2";
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faTrash, faPenToSquare, faLock, faLockOpen } from '@fortawesome/free-solid-svg-icons';
import {useTimeFormatter} from "@/Composables/useTimeFormatter.js";

const props = defineProps({
    project: Object
});

const { secondsToHHMMSS } = useTimeFormatter();

function computeCurrentRegisteredTime(task, type = 'student') {
    let base = type === 'student' ? task.studentTimerEntries : task.timerEntries;
    if(!base) return 0;
    return base.reduce((acc, timerEntry) => {
        if (timerEntry.end_at === null) {
            return acc + (new Date().getTime() - new Date(timerEntry.start_at).getTime()) / 1000;
        }
        return acc + (new Date(timerEntry.end_at).getTime() - new Date(timerEntry.start_at).getTime()) / 1000;
    }, 0);
}

const confirmDelete = (project, task) => {
    Swal.fire({
        title: 'Czy na pewno chcesz usunąć to zadanie?',
        text: "Nie będziesz mógł cofnąć tej operacji!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Tak, usuń',
        cancelButtonText: 'Anuluj'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('teacher.projects.tasks.destroy', { project, task }));
        }
    });
};
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Projekt: {{ project.name }}
                </h2>
                <Link class="transition-all bg-red-500 hover:bg-red-600 rounded-md text-white py-2 px-3" :href="route('teacher.projects.close', {project: project})"  v-if="!project.closed">
                    <FontAwesomeIcon :icon="faLock" class="text-white"/>
                    Zakończ to zadanie
                </Link>
                <Link class="transition-all bg-green-500 hover:bg-green-600 rounded-md text-white py-2 px-3"  :href="route('teacher.projects.open', {project: project})" v-else>
                    <FontAwesomeIcon :icon="faLockOpen" class="text-white"/>
                    Otwórz to zadanie
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                    <div class="overflow-x-auto rounded-lg border border-gray-200">

                        <div class="flex items-center text-white text-sm font-bold px-4 py-3" :class="{'bg-blue-500': !project.closed, 'bg-red-500': project.closed}" role="alert">
                            <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z"/></svg>
                            <p v-if="!project.closed">
                                Projekt jest w trakcie realizacji
                            </p>
                            <p v-else>
                                Projekt został zakończony
                            </p>
                        </div>
                        <div class="flow-root rounded-b-lg border border-gray-100 py-3 shadow-sm">

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

                        <div class="flex justify-between items-center m-3 mt-20">
                            <h1 class="font-bold">Zadania</h1>
                            <a :href="route('teacher.projects.tasks.create',{'project': project})" class="underline">Dodaj nowe zadanie</a>
                        </div>
                        <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
                            <thead class="text-left">
                            <tr>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Nazwa</th>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Szacowana ilość godzin</th>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Przepracowana ilość godzin</th>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Student</th>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 text-center">Akcje</th>
                            </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">
                            <tr v-for="task in project['tasks']">
                                <td class="whitespace-nowrap px-4 py-2 text-gray-900">
                                    <p class="font-medium">{{ task.name }}</p>
                                    <p class="text-xs">{{ task.description }}</p>
                                </td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ task.hours }}</td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-900">
                                    {{ secondsToHHMMSS(computeCurrentRegisteredTime(task, 'global')) }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ task.student?.name ?? "Brak" }}</td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-700 text-center space-x-2">
                                    <FontAwesomeIcon
                                        @click="router.visit(route('teacher.projects.tasks.edit',{'project': project, 'task': task}))"
                                        class="cursor-pointer transition-all text-yellow-500 hover:text-yellow-600 text-xl"
                                        :icon="faPenToSquare"/>
                                    <FontAwesomeIcon
                                        @click="confirmDelete(project, task)"
                                        class="cursor-pointer transition-all text-green-500 hover:text-green-600 text-xl"
                                        :icon="faTrash"/>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <hr>

                        <div class="flex justify-between items-center m-3 mt-20">
                            <h1 class="font-bold">Studenci</h1>
                        </div>
                        <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
                            <thead class="text-left">
                            <tr>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Imię i nazwisko</th>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Numer grupy</th>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Przepracowany czas</th>
                            </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">
                            <tr v-for="student in project['students']">
                                <td class="whitespace-nowrap px-4 py-2 text-gray-900">{{ student.name }}</td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-900">
                                    {{ student.groups.find((group) => group.project_id === project.id).group_number }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-900">
                                    {{ secondsToHHMMSS(computeCurrentRegisteredTime(student)) }}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

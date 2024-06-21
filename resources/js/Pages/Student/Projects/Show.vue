<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Table } from "@protonemedia/inertiajs-tables-laravel-query-builder";
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faPlay, faPause } from '@fortawesome/free-solid-svg-icons';
import { router } from "@inertiajs/vue3";
import {ref, onBeforeUnmount, onMounted, reactive} from "vue";
import {useTimeFormatter} from "@/Composables/useTimeFormatter";

const props = defineProps({
    project: Object,
    user: Object,
    group: Object
});

const { secondsToHHMMSS } = useTimeFormatter();

const project = props.project;
const tasks = ref([...props.project.tasks]);
let studentTasksTimes = reactive({});
let tasksTimes = reactive({});
const intervals = {};

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

onMounted(() => {
    tasks.value.forEach(task => {
        task.current_student_registered_time = computeCurrentRegisteredTime(task);
        studentTasksTimes[task.id] = computeCurrentRegisteredTime(task);
        tasksTimes[task.id] = computeCurrentRegisteredTime(task,'all');
    });
});

function syncWithBackend() {
    router.reload({
        method: 'get',
        preserveScroll: true,
        preserveState: true
    });
}

function startTimer(task) {
    if (!intervals[task.id]) {
        intervals[task.id] = setInterval(() => {
            task.current_student_registered_time += 1;
            studentTasksTimes[task.id] += 1;
            tasksTimes[task.id] += 1;
            router.visit(route('student.projects.show', {project: project.id}), {
                preserveScroll: true,
                preserveState: false
            });
        }, 1000);
        router.visit(route('student.projects.tasks.timer.start', {project: project.id, task: task.id}), {
            preserveScroll: true,
            preserveState: false
        });
    }
}

function stopTimer(task) {
    if (intervals[task.id]) {
        clearInterval(intervals[task.id]);
        delete intervals[task.id];
    }
    router.get(route('student.projects.tasks.timer.stop', {project: project.id, task: task.id}), {
        preserveScroll: true,
        preserveState: false
    });
}

// onBeforeUnmount(() => {
//     Object.values(intervals).forEach(clearInterval);
// });

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
                                    <dt class="font-medium text-gray-900">Data oddania</dt>
                                    <dd class="text-gray-700 sm:col-span-2">{{ project.due_date }}</dd>
                                </div>

                                <div class="grid grid-cols-1 gap-1 p-3 sm:grid-cols-3 sm:gap-4">
                                    <dt class="font-medium text-gray-900">Ilość grup</dt>
                                    <dd class="text-gray-700 sm:col-span-2">{{ project.groups_count }}</dd>
                                </div>

                                <div class="grid grid-cols-1 gap-1 p-3 sm:grid-cols-3 sm:gap-4">
                                    <dt class="font-medium text-gray-900">Twoja grupa</dt>
                                    <dd class="text-gray-700 sm:col-span-2">{{ group.group_number }}</dd>
                                </div>

                                <div class="grid grid-cols-1 gap-1 p-3 sm:grid-cols-3 sm:gap-4">
                                    <dt class="font-medium text-gray-900">Ilość studentów</dt>
                                    <dd class="text-gray-700 sm:col-span-2">{{ project.students_count }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div class="flex justify-between items-center m-3 mt-20">
                            <h1 class="font-bold">Zadania</h1>
                        </div>
                        <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
                            <thead class="text-left">
                            <tr>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Nazwa</th>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Szacowana ilość
                                    godzin
                                </th>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Student</th>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Zarejestrowany czas ogólnie</th>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Twój zarejestrowany czas</th>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 text-center">Akcje</th>
                            </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">
                            <tr v-for="task in tasks" :key="task.id">
                                <td class="whitespace-nowrap px-4 py-2 text-gray-900">
                                    <p class="font-medium">{{ task.name }}</p>
                                    <p class="text-xs">{{ task.description }}</p>
                                </td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ task.hours }}</td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{
                                        task.student?.name ?? "Brak"
                                    }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-700">
                                    {{ secondsToHHMMSS(tasksTimes[task.id]) }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-700">
                                    {{ secondsToHHMMSS(studentTasksTimes[task.id]) }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-700">
                                    <template v-if="task.students?.find((student) => student.id === user.id)">
                                        <div class="space-x-2 text-xl text-center">
                                            <FontAwesomeIcon
                                                @click="startTimer(task)"
                                                class="cursor-pointer transition-all text-green-500 hover:text-green-600"
                                                :icon="faPlay"
                                                v-if="!task.timerEntries?.find((timerEntry) => timerEntry.end_at === null)"/>
                                            <FontAwesomeIcon
                                                @click="stopTimer(task)"
                                                class="cursor-pointer transition-all text-red-500 hover:text-red-600"
                                                :icon="faPause"
                                                v-else/>
                                        </div>
                                    </template>
                                    <template v-else>
                                        ---
                                    </template>
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
                            <tr v-for="student in project['students']" :key="student.id">
                                <td class="whitespace-nowrap px-4 py-2 text-gray-900">{{ student.name }}</td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-900">
                                    {{ student.groups.find((group) => group.project_id === project.id).group_number }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-900">
                                    {{ secondsToHHMMSS(computeCurrentRegisteredTime(student, 'global')) }}
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

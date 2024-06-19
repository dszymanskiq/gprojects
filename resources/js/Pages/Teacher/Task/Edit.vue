<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {useForm} from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    project: Object,
    task: Object
})

const form = useForm({
    name: props.project.name,
    hours: props.project.name,
})
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Projekt: {{ project.name }}
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <form @submit.prevent="form.post(route('teacher.projects.tasks.update',{'project': project, 'task': task['data']}))" class="space-y-6">
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="name" value="Nazwa" />
                            <TextInput
                                id="name"
                                v-model="form.name"
                                type="text"
                                class="mt-1 block w-full"
                            />
                            <InputError :message="form.errors.name" class="mt-2" />
                        </div>
                        <div class="col-span-6 sm:col-span-4">
                            <InputLabel for="hours" value="Szacowanych godzin" />
                            <TextInput
                                id="hours"
                                v-model="form.hours"
                                type="text"
                                class="mt-1 block w-full"
                            />
                            <InputError :message="form.errors.hours" class="mt-2" />
                        </div>

                        <PrimaryButton>
                            Zapisz
                        </PrimaryButton>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

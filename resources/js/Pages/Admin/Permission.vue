<script setup>
import LayoutAuthenticated from '@/Layouts/LayoutAuthenticated.vue';
import Pagination from '@/Components/Pagination.vue';
import { useForm } from '@inertiajs/vue3';
import SectionMain from '@/Components/SectionMain.vue'
import { Head } from '@inertiajs/vue3'
defineProps({
    permissions: Object,
});

const form = useForm({
    id: null,
    name: null,
});
const edit = (permission) => {
    form.id = permission.id;
    form.name = permission.name
}

const update = () => {
    form.id = permission.id;
    form.name = permission.name
}
</script>

<template>
    <LayoutAuthenticated title="Dashboard">

        <Head title="Permissions" />
        <SectionMain>
            <div class="overflow-x-auto relative shadow-md sm:rounded-lg mt-5">
                <table class="w-full text-xs text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="py-3 px-6 text-xs">STT</th>
                            <th scope="col" class="py-3 px-6 text-xs">name</th>
                            <th scope="col" class="py-3 px-6 text-xs">
                                <span class="sr-only">Edit</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(permission, index) in permissions.data" :key="index"
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{
                                index + 1 }}</th>
                            <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{
                                permission.name }}</th>
                            <td class="py-4 px-6 text-right">
                                <button @click="edit(permission)" type="button" data-toggle="modal"
                                    data-target="#exampleModal"
                                    class="inline-block px-6 py-2.5 bg-gray-200 text-gray-700 font-black text-xs leading-tight uppercase rounded shadow-md hover:bg-gray-300 hover:shadow-lg focus:bg-gray-300 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-400 active:shadow-lg transition duration-150 ease-in-out mx-2">Edit</button>
                                <button type="button" @click="Delete(permission.id)"
                                    class="inline-block px-6 py-2.5 bg-red-500 text-white font-black text-xs leading-tight uppercase rounded shadow-md hover:bg-red-600 hover:text-white hover:shadow-lg focus:bg-gray-900 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-900 active:shadow-lg transition duration-150 ease-in-out">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <pagination :links="permissions.links" />
        </SectionMain>
    </LayoutAuthenticated>
</template>

<template>
    <div class="flex items-center">
        <div class="flex w-full bg-white rounded-lg shadow">

            <input
                class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                type="search" name="search" placeholder="Search…" :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)" />
        </div>
        <button class="ml-3 text-gray-500 hover:text-gray-700 focus:text-indigo-500 text-sm dark:text-white" type="button"
            @click="$emit('reset')">Reset</button>
    </div>
</template>

<script>


export default {

    props: {
        modelValue: String,
        maxWidth: {
            type: Number,
            default: 300,
        },
    },
    emits: ['update:modelValue', 'reset'],
}
</script>

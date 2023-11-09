
<script setup>
import { computed, ref, inject, watch, toRef, onMounted, onUnmounted } from 'vue'

import { useForm } from '@inertiajs/vue3';
import CardBoxModal from '@/Components/CardBoxModal.vue'
import { router } from '@inertiajs/vue3'
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { useTreeStore } from '@/stores/tree.js'
import { emitter } from '@/composable/useEmitter';



const swal = inject('$swal')
const store = useTreeStore()
const form = useForm({
    id: null,
    name: null,
    images: null,
    description: null,
    address: null,
    status: null,
    state: null,
    user_manual: null,
    numberOfBathrooms: null,
    terms_policy: null,
    price: null,

});
onMounted(() => {
    console.log('mounted!')

})
const editMode = computed(
    {
        get() {
            return store.editMode;
        },
        set(value) {
            store.changeEditMode(value)
        }
    }
)
// const apartment = inject('apartment')

// if (apartment) {
//     form.acreage = apartment.acreage
// }
onMounted(() => {
    emitter.on('editApartment', async (data) => {

        form.id = data.id
        form.name = data.name
        form.description = data.description
        form.acreage = data.acreage
        form.price = data.price
        form.direction = data.direction

        form.status = data.status
        form.address = data.address


    });


})
onUnmounted(() => {
    emitter.off('editApartment', listener)
})
const listener = (data) => {

}
const isModalTree = computed({

    get() {
        return store.isModalTree;
    },
    set(value) {
        store.changeisModalTree(value)
        store.setApartment(null)
    }

})
const changeStatus = () => {
    store.changeStatus()
}
const isModalActive = ref(false)
const isImport = ref(false)
const props = defineProps({
    land: Object,
})


const saveTree = () => {
    if (editMode.value == true) {
        form.post(route('admin.land.tree.update', form.id), {
            onError: () => {

                isModalTree.value = true;
                editMode.value = true
            },
            onSuccess: () => {
                form.reset('id', 'image', 'name')
                isModalTree.value = false;
                editMode.value = false
            }
        });
    }
    else {
        form.post(route('admin.land.tree.store', props.land.id), {
            onError: () => {
                isModalTree.value = true;
                editMode.value = false
            },
            onSuccess: () => {
                form.reset('id', 'image', 'name')
                isModalTree.value = false;
                editMode.value = false
            },
        });
    }

}


</script>

<template>
    <div>
        <!-- {{ apartment }} -->
        <CardBoxModal v-model="isModalTree" buttonLabel="Save" has-cancel @confirm="saveTree" button="warning"
            :title="editMode ? 'Update Apartment' : 'Create Apartment'">
            <div v-if="form.progress" class="w-full bg-gray-200 rounded-full dark:bg-gray-700">
                <div class="bg-blue-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full"
                    :style="`width: ${form.progress.percentage}%`"> {{ form.progress.percentage }}%</div>
            </div>
            <div class="mt-4 pt-5 overflow-auto">


                <div
                    class="grid  gap-4 my-4 min-[320px]:grid-cols-2 min-[320px]:my-0 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2 2xl:grid-cols-2">
                    <div>
                        <div class="my-4">
                            <label class="input w-full" for="recipient-name">
                                <input class="input__field border" type="text" placeholder="" v-model="form.name">
                                <span class="input__label bg-gray-50 text-lg" style="background-color: #fff;">
                                    Name</span>
                            </label>
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>
                        <div class="my-4">
                            <label class="input w-full" for="recipient-name">
                                <input class="input__field border" type="text" placeholder="" v-model="form.name">
                                <span class="input__label bg-gray-50 text-lg" style="background-color: #fff;">
                                    status</span>
                            </label>
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>
                        <div class="py-4">
                                <label class="input w-full" for="recipient-name">
                                    <input class="input__field border" type="text" placeholder="" v-model="form.address">
                                    <span class="input__label bg-gray-50 text-lg" style="background-color: #fff;">Địa
                                        chỉ</span>
                                </label>
                                <InputError class="mt-2" :message="form.errors.address" />
                            </div>

                            <div class="py-4">
                                <label class="input w-full" for="recipient-name">
                                    <input class="input__field border" type="number" placeholder="" v-model="form.price">
                                    <span class="input__label bg-gray-50 text-lg" style="background-color: #fff;">Giá
                                    </span>
                                </label>
                                <InputError class="mt-2" :message="form.errors.price" />
                            </div>

                    </div>
                    <div>
                        

                        <div class="my-4">
                            <label class="input w-full" for="recipient-name">
                                <textarea name="map_cors" id="" rows="5" class="input__field border"
                                    v-model="form.description"></textarea>
                                <span class="input__label bg-gray-50 text-lg" style="background-color: #fff;">Mô
                                    tả</span>
                            </label>
                            <InputError class="mt-2" :message="form.errors.description" />
                        </div>
                         <div class=" ">
                                <InputLabel for="name_amenities" value="Upload Image Layout" />
                                <label for="dropzone-image_url"
                                    class="flex flex-col items-center justify-center w-full h-36 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6 px-2">
                                        <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m²-2 2 2" />
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400 text-center"><span
                                                class="font-semibold">Click
                                                to upload</span> or drag and drop</p>


                                    </div>

                                    <input id="dropzone-images" type="file" class="hidden"
                                        @input="form.images = $event.target.files" multiple accept="image/*" />
                                </label>
                                <InputError class="mt-2" :message="form.errors.images" />
                            </div>
                    </div>
                    <!-- <div>
                       
                    </div> -->
                </div>

            </div>
        </CardBoxModal>

    </div>
</template>


<style scoped>
.input {
    position: relative;
}

:root {
    --color-light: white;
    --color-dark: #212121;
    --color-signal: #252525;
    --color-background: var(--color-light);
    --color-text: var(--color-dark);
    --color-accent: var(--color-signal);
    --size-bezel: .5rem;
    --size-radius: 8px;
    line-height: 1.4;
    font-family: "Inter", sans-serif;
    font-size: calc(.6rem + .4vw);
    color: var(--color-text);
    background: var(--color-background);
    font-weight: 300;
    /* padding: 0 calc(var(--size-bezel) * 3); */
}

.input__field:not(:placeholder-shown)+.input__label {
    transform: translate(0.25rem, -65%) scale(0.8);
    color: var(--color-accent);
}

.input__field {
    box-sizing: border-box;
    display: block;
    width: 100%;
    border: 1px solid rgb(163, 163, 163);
    padding: 0.5rem;
    color: rgb(163, 163, 163);
    background: transparent;
    border-radius: 8px;
}

.input__label {
    color: rgb(32, 32, 32) !important;
    position: absolute;
    left: 0;
    top: 0;
    padding: calc(var(--size-bezel) * 0.75) calc(var(--size-bezel) * .5);
    margin: calc(var(--size-bezel) * 0.75 + 3px) calc(var(--size-bezel) * .5);

    white-space: nowrap;
    transform: translate(0, 0) !important;
    transform-origin: 0 0;
    background: #ffffff;
    transition: transform 120ms ease-in;

    line-height: 1.2;
    transform: translate(0.25rem, -65%) scale(0.8) !important;
}
</style>

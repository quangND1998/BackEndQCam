<script setup>
import { computed, ref, inject, watch, toRef } from 'vue'
import LayoutAuthenticated from '@/Layouts/LayoutAuthenticated.vue';
import { Link } from '@inertiajs/vue3';
import Icon from '@/Components/Icon.vue';
import Breadcrumb from '@/Pages/Project/Breadcrumb.vue'
defineProps({
    project: Object,
    crumbs: Array
})
</script>

<template>
    <LayoutAuthenticated>
        <Breadcrumb :crumbs="crumbs" />
        <div class="px-5  mx-5 bg-white  dark:bg-gray-500 rounded-lg"
            style="box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;">
            <ul class="flex flex-wrap">
                <li
                    class="mx-5 text-base font-medium text-[#6C757D] hover:text-[#FF9B00] dark:text-white inline-block my-2">

                    <a href="" class="flex">
                        <Icon icon="info" class=" mr-2" />Project Information
                    </a>
                </li>
                <li class="mx-5 text-base font-medium text-[#6C757D] hover:text-[#FF9B00] flex dark:text-white inline-block my-2"
                    v-tooltip.top="'Block'"
                    :class="{ ' text-[#FF9B00] border-b-2 border-yellow-600': $page.component.includes('Block') }">

                    <Link :href="route('admin.project.blocks.index', project.id)" class="flex">
                    <Icon icon="buildings" class=" mr-2" />Blocks</Link>
                </li>
                <li class="mx-5 text-base font-medium text-[#6C757D] hover:text-[#FF9B00] flex dark:text-white inline-block my-2"
                    v-tooltip.top="'Amenities'"
                    :class="{ ' text-[#FF9B00] border-b-2 border-yellow-600': $page.component.includes('Amenities') }">

                    <Link :href="route('admin.project.amenities.index', project.id)" class="flex">
                    <Icon icon="amentities" class=" mr-2" />Amentities</Link>
                </li>
                <li class="mx-5 text-base font-medium text-[#6C757D] hover:text-[#FF9B00] flex dark:text-white inline-block my-2"
                    v-tooltip.top="'Image'"
                    :class="{ ' text-[#FF9B00] border-b-2 border-yellow-600': $page.component.includes('Images') }">

                    <Link :href="route('admin.project.images.index', project.id)" class="flex">
                    <Icon icon="gallery" class=" mr-2" />Images</Link>
                </li>
                <!-- <li class="mx-5 text-base font-medium text-[#6C757D] hover:text-[#FF9B00] flex dark:text-white" v-tooltip.top="'Video'">
                    <Icon icon="video_play" class=" mr-2" />
                    <a href="">Video</a>
                </li> -->

                <li class="mx-5 text-base font-medium text-[#6C757D] hover:text-[#FF9B00] flex dark:text-white inline-block my-2"
                    v-tooltip.top="'Brochure'"
                    :class="{ ' text-[#FF9B00] border-b-2 border-yellow-600': $page.component.includes('Brochure') }">
                    <Link :href="route('admin.project.brochures.index', project.id)" class="flex">
                    <Icon icon="clipboard" class=" mr-2" />
                    Brochure</Link>
                </li>
                <li class="mx-5 text-base font-medium text-[#6C757D] hover:text-[#FF9B00] flex dark:text-white inline-block my-2"
                    v-tooltip.top="'Price List'"
                    :class="{ ' text-[#FF9B00] border-b-2 border-yellow-600': $page.component.includes('PriceList') }">
                    <Link :href="route('admin.project.price-list.index', project.id)" class="flex">
                    <Icon icon="grid" class=" mr-2" /> Price List
                    </Link>
                </li>

                <li class="mx-5 text-base font-medium text-[#6C757D] hover:text-[#FF9B00] flex dark:text-white inline-block my-2"
                    v-tooltip.top="'Address'"
                    :class="{ ' text-[#FF9B00] border-b-2 border-yellow-600': $page.component.includes('Address') }">
                    <Link :href="route('admin.project.address.index', project.id)" class="flex">
                    <Icon icon="grid" class=" mr-2" /> Address
                    </Link>
                </li>
                <li class="mx-5 text-base font-medium text-[#6C757D] hover:text-[#FF9B00] flex dark:text-white inline-block my-2"
                    v-tooltip.top="'Setting'">
                    <Icon icon="setting" class=" mr-2" />
                    <a href="">Setting</a>
                </li>
            </ul>
        </div>
        <slot />

    </LayoutAuthenticated>
</template>

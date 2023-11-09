import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useTreeStore = defineStore('tree', () => {
    const editMode = ref(true);
    const tree = ref(null);
    const isModalTree = ref(false);
    const changeEditMode = (state) => {
        editMode.value = state
    }
    const setTree = (data) => {
        tree.value = data

    }
    const changeisModalTree = (state) => {
        isModalTree.value = state
    }

    return {
        editMode,
        tree,
        isModalTree,
        changeEditMode,
        setTree,
        changeisModalTree

    }
})
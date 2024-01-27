import { ref } from "vue";

const useDialog = () => {
    const data = ref(null)
  
    const showPopover = ref(false);
   

    const openDialog = (value) => {

        data.value = value
        showPopover.value = true

    }
    const closeDialog = () => {
       
        showPopover.value = false;
        data.value = null
    }

    return {
        data,
        
        showPopover,
        
        openDialog,
        closeDialog
    }
}
export default useDialog;
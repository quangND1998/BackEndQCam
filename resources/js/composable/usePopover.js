import { ref } from "vue";
import {
    arrow,
    useFloating,
    offset,
    flip,
    shift,
    autoUpdate
} from '@floating-ui/vue';
const usePopover = () => {
    const data = ref(null)
    const reference = ref(null);
    const floating = ref(null);
    const showPopover = ref(false);
    const floatingArrow = ref(null);
    const timeoutRef = ref();
    const { floatingStyles, middlewareData } = useFloating(reference, floating, {
        transform: true,
        middleware: [
            offset(10), flip(), shift(),
            arrow({ element: floatingArrow }),
        ],
        whileElementsMounted: autoUpdate,
    });

    const openPopover = (value) => {
        clearTimeout(timeoutRef.value);
        data.value = value
        showPopover.value = true
        timeoutRef.value = setTimeout(() => { showPopover.value = true }, 200);
        console.log(showPopover.value)
    }
    const closePopover = () => {
        clearTimeout(timeoutRef.value);
        showPopover.value = false;
        data.value = null
    }

    return {
        data,
        reference,
        floating,
        showPopover,
        floatingArrow,
        timeoutRef,
        floatingStyles,
        middlewareData,
        openPopover,
        closePopover
    }
}
export default usePopover;
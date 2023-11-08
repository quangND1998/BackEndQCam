import moment from 'moment';
import { router } from '@inertiajs/vue3'
export const useHelper = () => {

    const multipleSelect = (data) => {
        let object = Object.assign({}, data);
        // console.log(typeof object);
        // // // let first = Object.assign({}, object[0]);
        let array = [];
        $.each(object, function(key, value) {
            array.push(parseInt(value.id));
        });
        return array;
    }
    const changePageAction = (router, prama, query) => {
        router.get(route(router, prama), query, {
            preserveState: true

        });
    }
    return {
        multipleSelect,
        changePageAction


    }


}
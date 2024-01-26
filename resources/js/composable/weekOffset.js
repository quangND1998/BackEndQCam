import moment from 'moment';
import { router } from '@inertiajs/vue3'

    const calWeekOffset = (offset) => {
        const getWeekOffset = (offset) => {
            const currentWeek = moment().week();
            const targetWeek = currentWeek + offset;
            const targetDate = moment().week(targetWeek).startOf('week');

            const weekNumber = targetDate.week(); // Lấy tuần thứ bao nhiêu của năm
            const weekMonth = targetDate.week() - targetDate.clone().startOf('month').week() + 1; // Lấy tuần thứ bao nhiêu của tháng
            const targetStartDate = moment().week(targetWeek).startOf('week');
            const targetEndDate = moment().week(targetWeek).endOf('week');

            const month = targetDate.format('M');
            const year = targetDate.format('YYYY');

            let text = 'TUẦN ' + weekNumber + ' NĂM ' + year + ' (Tuần thứ ' + weekMonth + '/tháng ' + month + ")"
                        + ' TỪ NGÀY ' + targetStartDate.format('DD/MM/YYYY') + ' ĐẾN NGÀY ' + targetEndDate.format('DD/MM/YYYY');
            let text_week = 'Tuần ' + weekNumber + ' (T' + month + ')';
            let text_week_detail = 'Tuần ' + weekNumber + ' (Tuần thứ ' + weekMonth + ')';
            let list_date = [];
            const currentDate = targetStartDate.clone();

            while (currentDate <= targetEndDate) {
                const dayName = currentDate.format('DD/MM');
                list_date.push(dayName);
                currentDate.add(1, 'day');
            }
            // console.log(offset);
            return  [
                text,
                text_week,
                list_date,
                text_week_detail,
                targetStartDate.format('DD/MM/YYYY'),
                targetEndDate.format('DD/MM/YYYY')
            ];
        }
        const getOffset = (fromdate) =>{
            // Lấy tuần hiện tại
            if(fromdate == 0){
                return 0
            }
            var currentWeek = moment().week();

            // Định nghĩa tuần cần tính khoảng cách
            var targetWeek = moment('2024-02-18').week();

            console.log(currentWeek);
            console.log(targetWeek);
            // Tính khoảng cách giữa hai tuần
            var distance = Math.abs(currentWeek - targetWeek);

            return distance
        }

        return {
            getWeekOffset,
            getOffset
        }
    }
    export default calWeekOffset;

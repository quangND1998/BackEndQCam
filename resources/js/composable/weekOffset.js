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
            let text_full_week = 'Tuần ' + weekNumber + ' (Tuần thứ ' + weekMonth + ')';
            let list_date = [];
            let list_date_detail = [];
            const currentDate = targetStartDate.clone();
            const currentDateDetail = targetStartDate.clone();
            while (currentDate <= targetEndDate) {
                const dayName = currentDate.format('DD/MM');
                list_date.push(dayName);
                currentDate.add(1, 'day');
            }
            while (currentDateDetail <= targetEndDate) {
                const dayNameDetail = currentDateDetail.format('YYYY-MM-DD');
                list_date_detail.push(dayNameDetail);
                currentDateDetail.add(1, 'day');
                // console.log(currentDateDetail);
            }
            console.log(list_date_detail);
            return  [
                text,
                text_week,
                list_date,
                text_week_detail,
                targetStartDate.format('DD/MM/YYYY'),
                targetEndDate.format('DD/MM/YYYY'),
                list_date_detail
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
        const getWeekNumber = (date) => {
            var d = new Date(date.getFullYear(), 0, 1);
            var dayNum = date.getDay();
            if (dayNum == 0) dayNum = 7;
            d.setDate(d.getDate() + (7 - dayNum));
            return Math.ceil(d.getTime() / 604800000);
          }
        const getFormatDay = (date) => {
            var inputDate = moment(date);

            // Lấy thứ của ngày
            var dayOfWeek = inputDate.day();
            var weekOfYear = inputDate.week();

            // // Lấy tháng của ngày (từ 0-11, 0 là tháng 1)
            var month = inputDate.month() + 1;

            return dayOfWeek + "/" + weekOfYear + "/" + month;
            //  console.log(dayOfWeek + "/" + weekOfYear + "/" + month);
        }
        const MonthOffset = (fromdate) => {
            console.log(fromdate);
            var currentMonth = moment(fromdate).format('M');
            return currentMonth;
        }

        return {
            getWeekOffset,
            getOffset,
            MonthOffset,
            getFormatDay
        }
    }
    export default calWeekOffset;

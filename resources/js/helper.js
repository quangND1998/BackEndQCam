import moment from 'moment';
export const helper = {
    methods: {
        hasAnyPermission: function(permissions) {
            if (permissions == null) {
                return true
            }
            var allPermissions = this.$page.props.auth.can;
            var hasPermission = false;
            permissions.forEach(function(item) {
                if (allPermissions[item]) hasPermission = true;
            });
            return hasPermission;
        },
        hasAnyRoles: function(roles) {

            var allroles = this.$page.props.auth.can_role;

            var hasRole = false;
            roles.forEach(function(item) {

                if (allroles[item]) hasRole = true;

            });
            return hasRole;
        },
        hasRoles: function(user, roles) {
            var hasRole = false;
            user.roles.forEach(function(item) {
                if (item.name == roles) hasRole = true;
            });
            return hasRole;
        },

        formatDate: function(value) {
            if (value) {
                return moment(String(value)).format('DD/MM/YYYY HH:mm:ss')
            }
        },
        formatDateOnly: function(value) {
            if (value) {
                return moment(String(value)).format('DD/MM/YYYY')
            }
        },
        formatPrice(value) {
            let val = (value / 1).toFixed(0).replace('.', ',')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        },
        currentDate(){
            const date = new Date();
            return moment(String(date)).format(' HH:mm DD/MM/YYYY')
        },
        getOffsetWeek(fromdate){
            // Lấy tuần hiện tại
            var currentWeek = moment().isoWeek();

            // Định nghĩa tuần cần tính khoảng cách
            var targetWeek = moment(fromdate).isoWeek();

            // Tính khoảng cách giữa hai tuần
            var distance = Math.abs(currentWeek - targetWeek);
            return distance
        },
        getWeekOffset(offset) {
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
            return  [
                text,
                text_week,
                list_date,
                text_week_detail,
                targetWeek,
                list_date_detail
            ];
        },
        formatPriceShort(value) {
            // console.log(value.toString().length)
            let val = (value / 1).toFixed(0).replace('.', ',')
            if (value >= 1000000000) {

                // console.log('formatPriceShort', val.toString().substring(value.toString().length - 6, 0))
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".").substring(value.toString().length - 6, 0).replace(/.$/, "") + " " + "tỷ"
            } else if (value < 1000000000 && value >= 10000000) {

                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".").substring(0, 3).replace(/.$/, "") + " " + "triệu"
            } else if (value < 10000000 && value >= 1000000) {

                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".").substring(0, 4).replace(/.$/, "") + " " + "triệu"
            } else {
                return value.toLocaleString('it-IT', { style: 'currency', currency: 'VND' });
            }

        },
        formatCurrency(value, currency_type) {
            let currency = value.toLocaleString('it-IT', { style: 'currency', currency: currency_type });
            return currency;
        },
        bytesToHuman(bytes) {
            const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];

            if (bytes === 0) return '0 Bytes';

            const i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)), 10);

            if (i === 0) return `${bytes} ${sizes[i]}`;

            return `${(bytes / 1024 ** i).toFixed(1)} ${sizes[i]}`;
        },
        formatTimeDayMonthyear: function(value) {
            if (value) {
                return moment(String(value)).format('HH:mm DD/MM/YYYY ')
            }
        },
        hidePhoneNumber(phoneNumber) {
            if (phoneNumber) {
                var visibleDigits = 3; // Số lượng chữ số được hiển thị
                var hiddenDigits = phoneNumber.length - visibleDigits; // Số lượng chữ số bị ẩn

                // Tạo chuỗi dấu * có độ dài bằng số lượng chữ số bị ẩn
                var hiddenPart = '*'.repeat(hiddenDigits);

                // Lấy 3 chữ số cuối cùng của số điện thoại
                var visiblePart = phoneNumber.slice(-visibleDigits);

                // Kết hợp chữ số hiển thị và chữ số ẩn để tạo số điện thoại ẩn
                var hiddenPhoneNumber = hiddenPart + visiblePart;

                return hiddenPhoneNumber;
            }
            return null

        }
    },
};

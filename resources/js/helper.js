import moment from 'moment';
export const helper = {
    methods: {
        hasAnyPermission: function (permissions) {
            if (permissions == null) {
                return true
            }
            var allPermissions = this.$page.props.auth.can;
            var hasPermission = false;
            permissions.forEach(function (item) {
                if (allPermissions[item]) hasPermission = true;
            });
            return hasPermission;
        },
        hasAnyRoles: function (roles) {

            var allroles = this.$page.props.auth.roles;

            var hasRole = false;
            roles.forEach(function (item) {

                if (allroles[item]) hasRole = true;

            });
            return hasRole;
        },
        hasRoles: function (user, roles) {
            var hasRole = false;
            user.roles.forEach(function (item) {
                if (item.name == roles) hasRole = true;
            });
            return hasRole;
        },

        formatDate: function (value) {
            if (value) {
                return moment(String(value)).format('DD/MM/YYYY HH:mm:ss')
            }
        },
        formatDateOnly: function (value) {
            if (value) {
                return moment(String(value)).format('DD/MM/YYYY')
            }
        },
        formatPrice(value) {
            let val = (value / 1).toFixed(0).replace('.', ',')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
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
        formatTimeDayMonthyear: function (value) {
            if (value) {
                return moment(String(value)).format('HH:mm DD/MM/YYYY ')
            }
        },
    },
};

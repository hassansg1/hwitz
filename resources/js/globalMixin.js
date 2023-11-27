import Moment from 'moment-timezone';

Moment.tz.setDefault("America/Chicago");
export default {
    methods: {
        showLoader() {
            var loading_icon = '<div class="dataprocessing"><div class="loading-icon"><i class="fa fa-spinner fa-spin"></i></div></div>';
            $("body").append(loading_icon);
        },
        removeLoader() {
            $("div.dataprocessing").remove();

        },
        formatDate(date) {
            const momentDate = Moment(date);
            if (momentDate.isValid()) {
                return momentDate.format('MM/DD/YYYY');
            } else {
                return Moment().format('MM/DD/YYYY');
            }
        },
        formatDateStrict(date) {
            const momentDate = Moment(date);
            if (momentDate.isValid()) {
                return momentDate.format('MM/DD/YYYY');
            } else {
                return "-";
            }
        },
        stripCharacter(text, character, replace = "") {
            return text.replace(character, replace);
        },
        onlyNumber($event) {
            let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
            if ((keyCode < 48 || keyCode > 57)) { // 46 is dot
                $event.preventDefault();
            }
        },
        formatDateTime(date) {
            return Moment(date).format('MM/DD/YYYY hh:mm a')
        },
        printDiv(el) {
            var prtContent = document.getElementById(el);
            var WinPrint = window.open();
            WinPrint.document.write(prtContent.innerHTML);
            WinPrint.document.close();
            WinPrint.focus();
            WinPrint.print();
            WinPrint.close();
        },
        formatMoneyWithCommas(date) {
            if (!date)
                date = 0;
            return "$" + date.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
        chargeAmountFormatter(date) {
            let string = "$(" + date.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ")";
            string = string.replace("-", "");
            return string;
        },
        formatTime(date) {
            return Moment(date).format('hh:mm:ss a')
        },
        convertToHoursMins(num) {
            var hours = Math.floor(num / 60);
            var minutes = num % 60;
            return hours + ":" + minutes;
        },
        addDaysToDate(date, days) {
            return Moment(date).add(days, "days").format('MM/DD/YYYY')
        },
        randomNumber() {
            return Math.ceil(Math.random() * 1000000)
        },
        processTaskSubject(task) {
            let output = '';

            if (task.subject_type === 'Document') {
                if (task.packet_name) {
                    output += task.packet_name + '\n';
                }
                if (task.templateString) {
                    output += task.templateString + '\n';
                }
            }

            if (task.name === 'Approve Profile Image') {
                if (task.userstring) {
                    output += task.userstring;
                }
            }

            return output;
        },
        usStates() {
            const states = {
                'AL': 'Alabama AL',
                'AK': 'Alaska AK',
                'AZ': 'Arizona AZ',
                'AR': 'Arkansas AR',
                'CA': 'California CA',
                'CO': 'Colorado CO',
                'CT': 'Connecticut CT',
                'DE': 'Delaware DE',
                'DC': 'District of Columbia DC',
                'FL': 'Florida FL',
                'GA': 'Georgia GA',
                'HI': 'Hawaii HI',
                'ID': 'Idaho ID',
                'IL': 'Illinois IL',
                'IN': 'Indiana IN',
                'IA': 'Iowa IA',
                'KS': 'Kansas KS',
                'KY': 'Kentucky KY',
                'LA': 'Louisiana LA',
                'ME': 'Maine ME',
                'MD': 'Maryland MD',
                'MA': 'Massachusetts MA',
                'MI': 'Michigan MI',
                'MN': 'Minnesota MN',
                'MS': 'Minnesota MS',
                'MO': 'Missouri MO',
                'MT': 'Montana MT',
                'NE': 'Nebraska NE',
                'NV': 'Nevada NV',
                'NH': 'New Hampshire NH',
                'NJ': 'New Jersey NJ',
                'NM': 'New Mexico NM',
                'NY': 'New York NY',
                'NC': 'North Carolina NC',
                'ND': 'North Dakota ND',
                'OH': 'Ohio OH',
                'OK': 'Oklahoma OK',
                'OR': 'Oregon OR',
                'PA': 'Pennsylvania PA',
                'RI': 'Rhode Island RI',
                'SC': 'South Carolina SC',
                'SD': 'South Dakota SD',
                'TN': 'Tennessee TN',
                'TX': 'Texas TX',
                'UT': 'Utah UT',
                'VT': 'Vermont VT',
                'VA': 'Virginia VA',
                'WA': 'Washington WA',
                'WV': 'West Virginia WV',
                'WI': 'Wisconsin WI',
                'WY': 'Wyoming WY',
            }
            return states;
        },
        getTimeAgo(datetimeString) {
            return Moment(datetimeString).fromNow();
        },

        getAttachedFilesObjects(id) {
            let returnedFiles = [];
            var files = $(id)[0].files;
            for (var i = 0; i < files.length; i++) {
                var fileup = files[i];
                returnedFiles.push(fileup);
            }
            console.log(returnedFiles);
            return returnedFiles;
        },

        capitalizeFirstLetter(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        },
        getNext20Years() {
            const currentYear = new Date().getFullYear();
            const years = [];
            for (let i = 0; i < 20; i++) {
                const year = currentYear + i;
                years.push({id: i + 1, name: year});
            }
            return years;
        },
        isNumber(n) { return /^-?[\d.]+(?:e-?\d+)?$/.test(n); }

    }
}
  